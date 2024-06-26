<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Country;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function view(Request $request)
    {
        $user = $request->user();
        $customer = $user->customer;
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
        $countries = Country::query()->orderBy('name')->get();

        return view('profile.view', compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }

    public function edit()
    {
        $user = Auth::user();
        $customer = $user->customer;
        $billingAddress = $customer->billingAddress;
        $shippingAddress = $customer->shippingAddress;
        $countries = Country::all();

        return view('profile.edit', compact('user', 'customer', 'billingAddress', 'shippingAddress', 'countries'));
    }

    public function store(ProfileRequest $request)
    {
        try {
            $customerData = $request->validated();
            $shippingData = $customerData['shipping'];
            $billingData = $customerData['billing'];

            $user = $request->user();
            $customer = $user->customer;

            $customer->update($customerData);

            if ($customer->shippingAddress) {
                $customer->shippingAddress->update($shippingData);
            } else {
                $shippingData['customer_id'] = $customer->user_id;
                $shippingData['type'] = AddressType::Shipping->value;
                CustomerAddress::create($shippingData);
            }

            if ($customer->billingAddress) {
                $customer->billingAddress->update($billingData);
            } else {
                $billingData['customer_id'] = $customer->user_id;
                $billingData['type'] = AddressType::Billing->value;
                CustomerAddress::create($billingData);
            }

            $request->session()->flash('success_message', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            $request->session()->flash('error_message', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }

        return redirect()->route('profile');
    }

    public function passwordUpdate(PasswordUpdateRequest $request)
    {
        try {
            $user = $request->user();
            $passwordData = $request->validated();

            if (!Hash::check($passwordData['old_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'old_password' => 'Password lama tidak sesuai.',
                ]);
            }

            $user->password = Hash::make($passwordData['new_password']);
            $user->save();

            $request->session()->flash('success_message', 'Password berhasil diperbarui.');

        } catch (ValidationException $e) {
            return redirect()->route('profile')
                             ->withErrors($e->errors())
                             ->withInput();
        } catch (\Exception $e) {
            $request->session()->flash('error_message', 'Terjadi kesalahan saat memperbarui password: ' . $e->getMessage());
        }

        return redirect()->route('profile');
    }
}
