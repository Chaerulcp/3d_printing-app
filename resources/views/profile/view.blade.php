<x-app-layout>
    <div class="container mx-auto lg:w-2/3 p-5">
        <div class="container mx-auto lg:w-2/3 p-5">
            @if(session('success_message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success_message') }}
                </div>
            @endif
            @if(session('error_message'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error_message') }}
                </div>
            @endif
    
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        
    </div>


    <div x-data="{
        editMode: false,
        countries: {{ json_encode($countries) }},
        billingAddress: {
            address1: '{{ old('billing.address1', $billingAddress->address1) }}',
            address2: '{{ old('billing.address2', $billingAddress->address2) }}',
            city: '{{ old('billing.city', $billingAddress->city) }}',
            state: '{{ old('billing.state', $billingAddress->state) }}',
            country_code: '{{ old('billing.country_code', $billingAddress->country_code) }}',
            zipcode: '{{ old('billing.zipcode', $billingAddress->zipcode) }}',
        },
        shippingAddress: {
            address1: '{{ old('shipping.address1', $shippingAddress->address1) }}',
            address2: '{{ old('shipping.address2', $shippingAddress->address2) }}',
            city: '{{ old('shipping.city', $shippingAddress->city) }}',
            state: '{{ old('shipping.state', $shippingAddress->state) }}',
            country_code: '{{ old('shipping.country_code', $shippingAddress->country_code) }}',
            zipcode: '{{ old('shipping.zipcode', $shippingAddress->zipcode) }}',
        },
        getCountryStates(countryCode) {
            const country = this.countries.find(c => c.code === countryCode);
            return country && country.states ? JSON.parse(country.states) : {};
        }
    }" class="container mx-auto lg:w-2/3 p-5">
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        {{ session('error') }}
    </div>
    @endif
    
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
            <div class="bg-white p-3 shadow rounded-lg md:col-span-2">
                <form x-show="editMode" x-cloak action="{{ route('profile.update') }}" method="post">
                    @csrf
                    <h2 class="text-xl font-semibold mb-2">Detail Profil</h2>
                    <!-- Form details -->
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <x-input
                            type="text"
                            name="first_name"
                            value="{{ old('first_name', $customer->first_name) }}"
                            placeholder="Nama Depan"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        <x-input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name', $customer->last_name) }}"
                            placeholder="Nama Belakang"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>
                    <!-- Email field -->
                    <div class="mb-3">
                        <x-input
                            type="email"
                            name="email"
                            value="{{ $user->email }}"
                            placeholder="Email Anda"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            readonly
                        />
                    </div>
                    <!-- Phone number -->
                    <div class="mb-3">
                        <x-input
                            type="text"
                            name="phone"
                            value="{{ old('phone', $customer->phone) }}"
                            placeholder="Nomor Telepon"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                    </div>

                    <!-- Billing address section -->
                    <h2 class="text-xl mt-6 font-semibold mb-2">Alamat Tagihan</h2>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="billing[address1]"
                                x-model="billingAddress.address1"
                                placeholder="Alamat 1"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="billing[address2]"
                                x-model="billingAddress.address2"
                                placeholder="Alamat 2"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="billing[city]"
                                x-model="billingAddress.city"
                                placeholder="Kota"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="billing[zipcode]"
                                x-model="billingAddress.zipcode"
                                placeholder="Kode Pos"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <select
                                name="billing[country_code]"
                                x-model="billingAddress.country_code"
                                @change="billingAddress.state = ''"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            >
                                <option value="">Pilih negara</option>
                                <template x-for="country of countries" :key="country.code">
                                    <option :value="country.code" x-text="country.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <template x-if="getCountryStates(billingAddress.country_code)">
                                <select
                                    name="billing[state]"
                                    x-model="billingAddress.state"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                                >
                                    <option value="">Pilih Provinsi</option>
                                    <template x-for="[code, state] of Object.entries(getCountryStates(billingAddress.country_code))" :key="code">
                                        <option :value="code" x-text="state"></option>
                                    </template>
                                </select>
                            </template>
                            <template x-if="!getCountryStates(billingAddress.country_code)">
                                <x-input
                                    type="text"
                                    name="billing[state]"
                                    x-model="billingAddress.state"
                                    placeholder="Provinsi"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                                />
                            </template>
                        </div>
                    </div>

                    <!-- Shipping address section -->
                    <div class="flex justify-between mt-6 mb-2">
                        <h2 class="text-xl font-semibold">Alamat pengiriman</h2>
                        <label for="sameAsBillingAddress" class="text-gray-700">
                            <input
                                @change="$event.target.checked ? shippingAddress = {...billingAddress} : ''"
                                id="sameAsBillingAddress"
                                type="checkbox"
                                class="text-purple-600 focus:ring-purple-600 mr-2"
                            >
                            Sama dengan alamat tagihan
                        </label>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="shipping[address1]"
                                x-model="shippingAddress.address1"
                                placeholder="Alamat 1"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="shipping[address2]"
                                x-model="shippingAddress.address2"
                                placeholder="Alamat 2"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="shipping[city]"
                                x-model="shippingAddress.city"
                                placeholder="Kota"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="shipping[zipcode]"
                                x-model="shippingAddress.zipcode"
                                placeholder="Kode Pos"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <select
                                name="shipping[country_code]"
                                x-model="shippingAddress.country_code"
                                @change="shippingAddress.state = ''"
                                class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                            >
                                <option value="">Pilih negara</option>
                                <template x-for="country of countries" :key="country.code">
                                    <option :value="country.code" x-text="country.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <template x-if="getCountryStates(shippingAddress.country_code)">
                                <select
                                    name="shipping[state]"
                                    x-model="shippingAddress.state"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                                >
                                    <option value="">Pilih Provinsi</option>
                                    <template x-for="[code, state] of Object.entries(getCountryStates(shippingAddress.country_code))" :key="code">
                                        <option :value="code" x-text="state"></option>
                                    </template>
                                </select>
                            </template>
                            <template x-if="!getCountryStates(shippingAddress.country_code)">
                                <x-input
                                    type="text"
                                    name="shipping[state]"
                                    x-model="shippingAddress.state"
                                    placeholder="Provinsi"
                                    class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                                />
                            </template>
                        </div>
                    </div>

                    <x-button type="submit" class="w-full">Memperbarui</x-button>
                </form>

                <!-- Display mode -->
                <div x-show="!editMode">
                    <h2 class="text-xl font-semibold mb-2">Detail Profil</h2>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="first_name"
                                value="{{ $customer->first_name }}"
                                placeholder="Nama Depan"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="last_name"
                                value="{{ $customer->last_name }}"
                                placeholder="Nama Belakang"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                    </div>
                    <div class="mb-3">
                        <x-input
                            type="email"
                            name="email"
                            value="{{ $user->email }}"
                            placeholder="Email Anda"
                            class="w-full border-gray-300 rounded"
                            disabled
                        />
                    </div>
                    <div class="mb-3">
                        <x-input
                            type="text"
                            name="phone"
                            value="{{ $customer->phone }}"
                            placeholder="Nomor Telepon"
                            class="w-full border-gray-300 rounded"
                            disabled
                        />
                    </div>

                    <!-- Billing address display -->
                    <h2 class="text-xl mt-6 font-semibold mb-2">Alamat Tagihan</h2>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="billing[address1]"
                                value="{{ $billingAddress->address1 }}"
                                placeholder="Alamat 1"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="billing[address2]"
                                value="{{ $billingAddress->address2 }}"
                                placeholder="Alamat 2"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="billing[city]"
                                value="{{ $billingAddress->city }}"
                                placeholder="Kota"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="billing[zipcode]"
                                value="{{ $billingAddress->zipcode }}"
                                placeholder="Kode Pos"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="billing[country_code]"
                                value="{{ $billingAddress->country_code }}"
                                placeholder="Negara"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="billing[state]"
                                value="{{ $billingAddress->state }}"
                                placeholder="Provinsi"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                    </div>

                    <!-- Shipping address display -->
                    <div class="flex justify-between mt-6 mb-2">
                        <h2 class="text-xl font-semibold">Alamat pengiriman</h2>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="shipping[address1]"
                                value="{{ $shippingAddress->address1 }}"
                                placeholder="Alamat 1"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="shipping[address2]"
                                value="{{ $shippingAddress->address2 }}"
                                placeholder="Alamat 2"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="shipping[city]"
                                value="{{ $shippingAddress->city }}"
                                placeholder="Kota"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="shipping[zipcode]"
                                value="{{ $shippingAddress->zipcode }}"
                                placeholder="Kode Pos"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <x-input
                                type="text"
                                name="shipping[country_code]"
                                value="{{ $shippingAddress->country_code }}"
                                placeholder="Negara"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                        <div>
                            <x-input
                                type="text"
                                name="shipping[state]"
                                value="{{ $shippingAddress->state }}"
                                placeholder="Provinsi"
                                class="w-full border-gray-300 rounded"
                                disabled
                            />
                        </div>
                    </div>

                    <x-button @click="editMode = true" class="w-full">Edit Profil</x-button>
                </div>
            </div>
            <div class="bg-white p-3 shadow rounded-lg">
                <!-- Change password form -->
                <form action="{{ route('profile_password.update') }}" method="post">
                    @csrf
                    <h2 class="text-xl font-semibold mb-2">Memperbarui Password</h2>
                    <div class="mb-3">
                        <x-input
                            type="password"
                            name="old_password"
                            placeholder="Password Lama"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        @error('old_password')
                            <div class="text-red-600 mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <x-input
                            type="password"
                            name="new_password"
                            placeholder="Password Baru"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        @error('new_password')
                            <div class="text-red-600 mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <x-input
                            type="password"
                            name="new_password_confirmation"
                            placeholder="Konfirmasi Password Baru"
                            class="w-full focus:border-purple-600 focus:ring-purple-600 border-gray-300 rounded"
                        />
                        @error('new_password_confirmation')
                            <div class="text-red-600 mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <x-button>Konfirmasi</x-button>
                </form>        
            </div>
        </div>
    </div>
    
</x-app-layout>

