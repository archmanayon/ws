<x-guest-layout>            
    
    <!-- Session Status -->
    <x-auth-session-status class="bg-[#1F2937] mb-4" :status="session('status')" />

    {{-- <form method="POST" action="{{ route('login') }}">
        @csrf --}}

        <!-- Email Address -->
        {{-- <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" 
                            type="email" name="email" 
                            :value="old('email')" 
                            required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div> --}}

        <!-- Password -->
        {{-- <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div> --}}

        <!-- Remember Me -->
        {{-- <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div> --}}

        {{-- <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request')) --}}
                {{-- <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a> --}}
            {{-- @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
            
        </div> --}}
    {{-- </form> --}}

    <x-link-button href="auth/google/redirect" 
        class="px-1 py-1 bg-[#6B7280] dark:bg-gray-800 w-48">
        {{-- <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 19">
            <path fill-rule="evenodd" d="M8.842 18.083a8.8 8.8 0 0 1-8.65-8.948 8.841 8.841 0 0 1 8.8-8.652h.153a8.464 8.464 0 0 1 5.7 2.257l-2.193 2.038A5.27 5.27 0 0 0 9.09 3.4a5.882 5.882 0 0 0-.2 11.76h.124a5.091 5.091 0 0 0 5.248-4.057L14.3 11H9V8h8.34c.066.543.095 1.09.088 1.636-.086 5.053-3.463 8.449-8.4 8.449l-.186-.002Z" clip-rule="evenodd"/>
        </svg>
        {{ __('Sign in with Google') }} --}}
        
        <img class="flex justify-center" src="images/ggl.png" alt="AiC" width="200" height="20">
    </x-link-button> 

        
    {{-- <div class="flex justify-end">
        <span class="py-4 text-white"> {{ 'or' }} <br></span>
        
    </div> --}}
    
</x-guest-layout>
