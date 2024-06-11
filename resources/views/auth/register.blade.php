<x-app-layout>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .bg-image {
            background-image: url('/images/bg_login.png'); /* Ganti dengan path gambar latar belakang yang sesuai */
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

        .register-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Memusatkan formulir */
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 400px; /* Atur lebar formulir sesuai kebutuhan */
        }
    </style>

    <div class="bg-image"></div>

    <form action="{{ route('register') }}" method="post" class="register-form">
        @csrf

        <h2 class="text-2xl font-semibold text-center mb-4">Buat sebuah akun</h2>
        <p class="text-center text-gray-500 mb-3">
            atau
            <a href="{{ route('login') }}" class="text-sm text-purple-700 hover:text-purple-600">
                login dengan akun yang ada
            </a>
        </p>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="mb-4">
            <x-input placeholder="Nama anda" type="text" name="name" :value="old('name')" class="w-full" />
        </div>
        <div class="mb-4">
            <x-input placeholder="Alamat email anda" type="email" name="email" :value="old('email')" class="w-full" />
        </div>
        <div class="mb-4">
            <x-input placeholder="Password" type="password" name="password" class="w-full" />
        </div>
        <div class="mb-4">
            <x-input placeholder="Masukan ulang Password" type="password" name="password_confirmation" class="w-full" />
        </div>

        <button class="btn-primary bg-blue-500 hover:bg-blue-600 active:bg-blue-700 w-full text-white font-bold py-2 px-4 rounded" type="submit">
            Buat akun
        </button>
    </form>
</x-app-layout>
