<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>3D Kreasi Coklat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs" defer></script>
    <style>
        /* Gaya untuk header */
        header {
            background: linear-gradient(to right, #1c3d72, #5293e6);
            color: black;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Gaya untuk tautan navigasi */
        .nav-link {
            display: block;
            padding: 1rem 1.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease;
            color: black;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Gaya untuk ikon keranjang belanja */
        .fa-shopping-cart {
            color: black;
            font-size: 1.5rem; /* Ukuran ikon */
            position: relative;
        }

        /* Gaya untuk counter keranjang belanja */
        .cart-counter {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
            padding: 0.25rem 0.5rem;
            background-color: red;
            color: white; /* Warna teks counter */
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold; /* Membuat teks lebih tebal */
        }

        /* Gaya untuk dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            min-width: 12rem;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            display: block;
            padding: 0.5rem 1rem;
            color: #333;
            text-decoration: none;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
<header x-data="{ mobileMenuOpen: false, cartItemsCount: {{ \App\Helpers\Cart::getCartItemsCount() }} }" @cart-change.window="cartItemsCount = $event.detail.count" class="bg-gradient-to-r from-blue-600 to-blue-400 text-black p-4 shadow-md">
    <div class="container mx-auto flex items-center justify-between">

        <div class="flex items-center">
            <a href="{{ route('home') }}">
                <img src="{{ asset('/images/3d-printer.png') }}" alt="3D Kreasi Coklat Logo" class="h-12 w-auto">
            </a>
            <span class="text-2xl font-semibold ml-2 hidden md:inline">Kreasi Coklat</span>
        </div>

        <nav class="hidden md:flex space-x-6">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
            <a href="{{ route('3d_models') }}" class="nav-link">3D Models</a>
            <a href="{{ route('contact.index') }}" class="nav-link">Contact</a>
        </nav>

        <div class="flex items-center space-x-4">
            <a href="{{ route('3d_models') }}" class="nav-link">Order Now</a>
            @if (Auth::check())
            <div class="dropdown" x-data="{ open: false }">
                <button @click="open = !open" class="nav-link">{{ Auth::user()->name }}</button>
                <div x-show="open" @click.away="open = false" class="dropdown-menu">
                    <a href="{{ route('profile') }}">Edit Profil</a>
                    <a href="{{ route('order.index') }}">Pesanan Saya</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Logout</button></form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="nav-link">Login</a>
            <a href="{{ route('register') }}" class="nav-link">Daftar</a>
            @endif
             <a href="{{ route('cart.index') }}" class="nav-link relative">
                <i class="fas fa-shopping-cart"></i>
                <span x-show="cartItemsCount > 0" class="cart-counter" x-text="cartItemsCount"></span>
            </a>
        </div>

        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden">
            <i class="fas fa-bars text-2xl"></i>
        </button>

    </div>

    <nav x-show="mobileMenuOpen" class="md:hidden mt-2 space-y-2">
        <a href="{{ route('home') }}" class="nav-link">Home</a>
        <a href="{{ route('3d_models') }}" class="nav-link">3D Models</a>
        <a href="{{ route('contact.index') }}" class="nav-link">Contact</a>
        @if (Auth::check())
            <a href="{{ route('cart.index') }}" class="nav-link">Keranjang</a>
            <a href="{{ route('profile') }}" class="nav-link">Edit Profil</a>
            <a href="{{ route('order.index') }}" class="nav-link">Pesanan Saya</a>
            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="nav-link">Logout</button></form>
        @else
            <a href="{{ route('login') }}" class="nav-link">Login</a>
            <a href="{{ route('register') }}" class="nav-link">Daftar</a>
        @endif
    </nav>
</header>
</body>
</html>
