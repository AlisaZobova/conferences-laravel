<x-guest-layout>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Firstname -->
        <div>
            <x-input-label for="firstname" :value="__('Firstname')"/>
            <x-text-input id="firstname" class="block mt-1 w-full" type="text" name="firstname"
                          :value="old('firstname')" required autofocus/>
            <x-input-error :messages="$errors->get('firstname')" class="mt-2"/>
        </div>

        <!-- Lastname -->
        <div>
            <x-input-label for="lastname" :value="__('Lastname')"/>
            <x-text-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')"
                          required autofocus/>
            <x-input-error :messages="$errors->get('lastname')" class="mt-2"/>
        </div>


        <!-- Type -->
        <div>
            <x-input-label for="type" :value="__('Type')"/>
            <select class="block mt-1 w-full" id="type" name="type">
                @foreach (['Listener', 'Announcer'] as $type)
                    <option>{{ $type }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('type')" class="mt-2"/>
        </div>

        <!-- Birthdate -->
        <div>
            <x-input-label for="birthdate" :value="__('Birthdate')"/>
            <x-text-input id="birthdate" class="block mt-1 w-full" type="date" name="birthdate"
                          :value="old('birthdate')" required autofocus/>
            <x-input-error :messages="$errors->get('birthdate')" class="mt-2"/>
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Phone')"/>
            <input class="block mt-1 w-full" id="intl-phone" type="tel" name="intl-phone" required autofocus>
            <input id="phone" type="hidden" name="phone">
            <input id="invalid-msg" type="hidden" value="Not valid number!" style="color:red;" readonly>
            <x-input-error :messages="$errors->get('phone')" class="mt-2"/>
        </div>

        <!-- Country -->
        <div>
            <x-input-label for="country" :value="__('Country')"/>
            <select class="block mt-1 w-full" id="country" name="country">
                @foreach ($countries as $country)
                    <option value="{{ $country->id  }}">{{ $country }}</option>
                @endforeach
            </select>
            {{--            <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country')" required autofocus />--}}
            <x-input-error :messages="$errors->get('country')" class="mt-2"/>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required/>
            <x-input-error :messages="$errors->get('email')" class="mt-2"/>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')"/>

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password"/>

            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')"/>

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required/>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>
