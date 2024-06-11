<x-app-layout>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .bg-image {
            background-image: url('/images/bg_login.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            min-width: 100vw;
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Memusatkan formulir */
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan */
            width: 400px; /* Atur lebar formulir sesuai kebutuhan */
        }
    </style>

    <div class="bg-image"></div>

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf
        <h2 class="text-2xl font-semibold text-center mb-5 text-gray-800">
            Login ke akun anda
        </h2>
        <p class="text-center text-gray-600 mb-6">
            atau
            <a href="{{ route('register') }}" class="text-sm text-purple-700 hover:text-purple-600">
                buat akun baru
            </a>
        </p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="mb-4">
            <x-input type="email" name="email" placeholder="Alamat email anda" :value="old('email')" class="w-full" />
        </div>
        <div class="mb-4">
            <x-input type="password" name="password" placeholder="Kata sandi anda" class="w-full" />
        </div>
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center">
                <input id="loginRememberMe" type="checkbox" class="rounded border-gray-300 text-purple-500 focus:ring-purple-500" />
                <label for="loginRememberMe" class="ml-2 text-sm text-gray-600">Ingat saya</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm text-purple-700 hover:text-purple-600">
                    Lupa Password?
                </a>
            @endif
        </div>
        <button class="btn-primary bg-blue-500 hover:bg-blue-600 active:bg-blue-700 w-full text-white font-bold py-2 px-4 rounded" type="submit">
            Login
        </button>
    </form>
</x-app-layout>
