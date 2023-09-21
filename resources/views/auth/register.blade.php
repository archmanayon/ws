
<x-guest-layout>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- active -->
        <div>            
            <x-text-input id="active" class="block mt-1 w-full" type="hidden" name="active" :value=1 required autofocus autocomplete="active" />
            <x-input-error :messages="$errors->get('active')" class="mt-2" />
        </div>

        <!-- Time Card -->
        <div class="mt-4">
            <x-input-label for="timecard" :value="__('Time Card')" />
            <x-text-input id="timecard" class="block mt-1 w-full" type="text" name="timecard" :value="old('timecard')" autofocus autocomplete="timecard" />
            <x-input-error :messages="$errors->get('timecard')" class="mt-2" />
        </div>

         <!-- Student ID -->
         <div class="mt-4">
            <x-input-label for="student_id" :value="__('Student ID')" />
            <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id" :value="old('student_id')" required autocomplete="student_id" />
            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
        </div>      

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>       

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Shift ID -->
        <div class="mt-4">
            <x-input-label for="shift_id" :value="__('Shift')" />
            <x-text-input id="shift_id" class="block mt-1 w-full" type="number" name="shift_id" :value="old('shift_id')" required autocomplete="shift_id" />
            <x-input-error :messages="$errors->get('shift_id')" class="mt-2" />
        </div>

         <!-- Role ID -->
         <div class="mt-4">
            <x-input-label for="role_id" :value="__('Role')" />
            <x-text-input id="role_id" class="block mt-1 w-full" type="number" name="role_id" :value="old('role_id')" required autocomplete="role_id" />
            <x-input-error :messages="$errors->get('role_id')" class="mt-2" />
        </div>
         <!-- Head ID -->
         <div class="mt-4">
            <x-input-label for="head_id" :value="__('Head Id')" />
            <x-text-input id="head_id" class="block mt-1 w-full" type="number" name="head_id" :value="old('head_id')" autocomplete="head_id" />
            <x-input-error :messages="$errors->get('head_id')" class="mt-2" />
        </div>

        <!-- Password -->
        {{-- <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div> --}}

        <!-- Confirm Password -->
        {{-- <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div> --}}

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
