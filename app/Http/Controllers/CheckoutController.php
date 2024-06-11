<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\Cart;
use App\Mail\NewOrderEmail;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    
    public function checkout(Request $request)
    {
        $user = $request->user();

          // Ensure user has both shipping and billing addresses
        if (!$user->customer->shippingAddress || !$user->customer->billingAddress) {
            return redirect()->route('profile')->with('error', 'Mohon lengkapi alamat pengiriman dan tagihan Anda sebelum melanjutkan.');
        } 

        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Retrieve products and cart items
        [$products, $cartItems] = Cart::getProductsAndCartItems();

        $orderItems = [];
        $item_details = [];
        $totalPrice = 0;

        // Prepare item details and calculate total price
        foreach ($products as $product) {
            $quantity = $cartItems[$product->id]['quantity'];
            $totalPrice += $product->price * $quantity;
            $item_details[] = [
                'id' => $product->id,
                'price' => $product->price,
                'quantity' => $quantity,
                'name' => $product->title,
            ];
            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price
            ];
        }

        // Validate total price
        if ($totalPrice <= 0) {
            return back()->with('error', __('validation.cart_empty'));
        }

        // Transaction details for Midtrans
        $transaction_details = [
            'order_id' => uniqid(), // Unique Order ID
            'gross_amount' => $totalPrice,
        ];

        // Customer details for Midtrans
        $customer_details = [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];

        // Combine transaction data
        $transactionData = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
        ];

        try {
            // Get Snap token from Midtrans
            $snapToken = Snap::getSnapToken($transactionData);

            // Create Order in database
            $orderData = [
                'total_price' => $totalPrice,
                'status' => OrderStatus::Unpaid,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ];
            $order = Order::create($orderData);

            // Create Order Items in database
            foreach ($orderItems as $orderItem) {
                $orderItem['order_id'] = $order->id;
                OrderItem::create($orderItem);
            }

            // Create Payment record in database
            $paymentData = [
                'order_id' => $order->id,
                'amount' => $totalPrice,
                'status' => PaymentStatus::Pending,
                'type' => 'midtrans',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'transaction_id' => $transaction_details['order_id'] // Store Midtrans transaction ID
            ];
            Payment::create($paymentData);

            // Clear cart items
            CartItem::where(['user_id' => $user->id])->delete();

            // Return view with Snap token and order details
            return view('checkout.midtrans', compact('snapToken', 'order'));
        } catch (\Exception $e) {
            // Handle exception and return failure view
            return view('checkout.failure', ['message' => 'Error creating transaction: ' . $e->getMessage()]);
        }
    }


    public function success(Request $request)
    {
        // Retrieve order ID from request
        $orderId = $request->input('order_id');
        $order = Order::findOrFail($orderId);

        // Update order status to Paid
        $order->status = OrderStatus::Paid;
        $order->save();

        // Update payment status to Paid
        $payment = Payment::where('order_id', $order->id)->first();
        if ($payment) {
            $payment->status = PaymentStatus::Paid;
            $payment->save();
        }

        // Send email notifications (if applicable)
        // $adminUsers = User::where('is_admin', 1)->get();
        // foreach ([...$adminUsers, $order->user] as $user) {
        //     Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
        // }

        // Return success view with order details
        return view('checkout.success', compact('order'));
    }

    public function failure(Request $request)
    {
        // Return failure view with appropriate message
        return view('checkout.failure', ['message' => ""]);
    }

    public function checkoutOrder(Order $order, Request $request)
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Validate order status before proceeding with checkout
        if ($order->isPaid() || in_array($order->status, ['shipped', 'completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'This order cannot be paid.');
        }

        // Prepare item details and total price for Midtrans
        $item_details = [];
        $totalPrice = 0;
        foreach ($order->items as $item) {
            $totalPrice += $item->unit_price * $item->quantity;
            $item_details[] = [
                'id' => $item->product_id,
                'price' => $item->unit_price,
                'quantity' => $item->quantity,
                'name' => $item->product->title,
            ];
        }

        // Generate or retrieve transaction ID
        $orderId = $order->payment->transaction_id ?? uniqid();

        // Update payment transaction ID if generated
        if (!$order->payment->transaction_id) {
            $order->payment->update(['transaction_id' => $orderId]);
        }

        // Transaction details for Midtrans
        $transaction_details = [
            'order_id' => $orderId,
            'gross_amount' => $totalPrice,
        ];

        // Customer details for Midtrans
        $customer_details = [
            'first_name' => $order->user->name,
            'email' => $order->user->email,
            'phone' => $order->user->phone,
        ];

        // Combine transaction data
        $transactionData = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
        ];

        try {
            // Get Snap token from Midtrans
            $snapToken = Snap::getSnapToken($transactionData);

            // Return view with Snap token and order details
            return view('checkout.midtrans', compact('snapToken', 'order'));
        } catch (\Exception $e) {
            // Handle exception and return failure view
            return view('checkout.failure', ['message' => 'Error creating transaction: ' . $e->getMessage()]);
        }
    }

    public function webhook(Request $request)
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Initialize Midtrans Notification
        $notif = new Notification();

        // Retrieve transaction status and details from Midtrans notification
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        // Retrieve payment record based on transaction ID
        $payment = Payment::where('transaction_id', $orderId)->firstOrFail();

        // Handle payment status based on Midtrans notification
        switch ($transaction) {
            case 'capture':
            case 'settlement':
                $payment->status = PaymentStatus::Paid;
                break;
            case 'pending':
                $payment->status = PaymentStatus::Pending;
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                $payment->status = PaymentStatus::Failed;
                break;
            default:
                Log::info('Unhandled Midtrans Notification Status: ' . $transaction);
                break;
        }

        // Save updated payment status
        $payment->save();

        // If payment is successful, update order status and notify users
        if ($payment->status == PaymentStatus::Paid) {
            $this->updateOrderAndSession($payment);
        }

        // Return success response to Midtrans
        // Return success response to Midtrans webhook
        return response()->json(['status' => 'success', 'message' => 'Webhook received successfully']);
    }

    // Update order status and notify users
    private function updateOrderAndSession(Payment $payment)
    {
        // Update payment status to Paid
        $payment->status = PaymentStatus::Paid;
        $payment->save();

        // Retrieve associated order
        $order = $payment->order;

        // Update order status to Paid
        $order->status = OrderStatus::Paid;
        $order->save();

        // Retrieve admin users
        $adminUsers = User::where('is_admin', 1)->get();

        // Notify admin users and customer about the order
        foreach ([...$adminUsers, $order->user] as $user) {
            Mail::to($user)->send(new NewOrderEmail($order, (bool)$user->is_admin));
        }
    }
}
