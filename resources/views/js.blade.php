@php
    use Illuminate\Support\Str;
    use \Carbon\Carbon;
@endphp

<x-guest-layout>
    <div class="lg:grid lg:grid-cols-2">

        <div class="py-6">
            <div class="lg:px-8 mx-6 sm:px-6 text-center">
                <div class "bg-white dark:bg-gray-800 mt-6 overflow-hidden px-6 py-4 shadow-md sm:rounded-lg w-full">
                    <div class="px-1 text-gray-900 dark:text-gray-100">
                        <div id="" class="text-5xl mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600                              focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        >
                            <div id="m_clock"
                                class="p-6 text-gray-900 dark:text-gray-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('punches') }}">
                @csrf

                <!-- USC ID Number -->
                <div>
                    <x-input-label for="student_id" :value="__('USC ID Number')" class="text-center" />
                    <x-text-input id="student_id" class="block mt-1 w-48 mx-auto"
                                    type="text" name="student_id"
                                    :value="old('student_id')"
                                    :placeholder="$employee->student_id??false"
                                    required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="punch_pw" :value="__('Password')" class="text-center"/>

                    <x-text-input id="punch_pw" class="block mt-1 w-48 mx-auto"
                                    type="password"
                                    name="punch_pw"
                                    required autocomplete="current-punch_pw" />

                    <x-input-error :messages="$errors->get('punch_pw')" class="mt-2" />
                </div>

                <div class="text-center">

                    <x-primary-button class=" text-2xl m-auto-3 py-1 px-6 mt-4 mx-auto w-auto">
                        {{ __('Punch with your ID') }}
                        {{-- Punch
                        {{ __( $in_out ? ($in_out == 'I'? 'In with your ID' :
                            'Out with your ID') :
                            'In with your ID') }} --}}
                    </x-primary-button>

                </div>
            </form>

        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" id="myElement">

                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        here

                    </div>

                    <div id=""
                        class="p-6 text-gray-900 dark:text-gray-100" >

                        there

                    </div>



                </div>
            </div>
        </div>

    </div>
</x-guest-layout>


