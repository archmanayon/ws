@php
    use Illuminate\Support\Str;
    use \Carbon\Carbon;
@endphp

<x-div-grid>
    <x-slot name="picture">

    </x-slot>

    <x-slot name="time">
        @if ($punches_today)
            <div class="justify-items-center lg:grid lg:grid-cols-3">
        @else
            <div class="justify-items-center lg:grid">
        @endif

            <!---------------first column------------------>
            <div class=" py-6">
                <div class="lg:px-8 mx-6 sm:px-6 text-center">
                    <div class ="bg-[#1F2937] text-white dark:bg-gray-800 mt-6 overflow-hidden px-6 py-4 shadow-md sm:rounded-lg w-full">
                        <div class="px-1 ">
                            <td>
                                    <div id="m_clock"
                                    class="text-5xl mt-2 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-600
                                        focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                                        >

                                    </div>
                                    <div id="sec"
                                    class="dark:bg-gray-700 dark:border-gray-700 dark:focus:border-indigo-600 dark:focus:ring-indigo-600 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 mt-0 order-gray-300 shadow-sm text-xl"
                                        >
                                    clock
                                    </div>


                            </td>

                            <form method="POST" action="{{ route('punches') }}">
                                @csrf

                                <!-- USC ID Number -->
                                <div>
                                    <x-input-label for="student_id" :value="__('USC ID Number')" class="block text-white dark:text-white font-medium mt-8 text-center text-sm" />
                                    <x-text-input id="student_id" class="block mt-1 w-48 mx-auto text-gray-900 dark:text-white"
                                                    type="text" name="student_id"
                                                    :value="old('student_id')"
                                                    :placeholder="$employee->student_id??false"
                                                    required autofocus autocomplete="username" />
                                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                                </div>

                                <!-- Password -->
                                <div class="mt-4">
                                    <x-input-label for="punch_pw" :value="__('Password')" class="text-white dark:text-white text-center"/>

                                    <x-text-input id="punch_pw" class="block mt-1 w-48 mx-auto text-gray-900 dark:text-white"
                                                    type="password"
                                                    name="punch_pw"
                                                    required autocomplete="current-punch_pw" />

                                    <x-input-error :messages="$errors->get('punch_pw')" class="mt-2" />
                                </div>


                                <div class="text-center">

                                    <x-primary-button class=" text-2xl m-auto-3 py-1 mt-8 mx-auto w-3/4">
                                        {{ __('Punch with your ID') }}
                                        {{-- Punch
                                        {{ __( $in_out ? ($in_out == 'I'? 'In with your ID' :
                                            'Out with your ID') :
                                            'In with your ID') }} --}}
                                    </x-primary-button>

                                    {{-- <br> {{ $ip }} --}}
                                    
                                    <input id="i_p" type="hidden" name="i_p" value="{{ $ip??0 }}"/>

                                </div>
                            </form>
                            {{-- <td>
                                {{$date_->format('F j, Y')  }}

                            </td> --}}
                        </div>
                    </div>
                </div>
            </div>

            <!---------------second column------------------>
            <div class="py-6">
                <div class="">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-6 ">
                        <div class="px-1 text-gray-900 dark:text-white">
                            <table class="block dark:text-white p-6 py-1 sm:px-6 text-gray-900 w-auto" >
                                @if ($punches_today)

                                    <th>
                                        {{ $employee->name??false }}
                                    </th>
                                    <tr><td class="text-gray-800">{{ "~" }}</td></tr>
                                    @foreach ( $punches_today as $shcp)
                                        <tr>
                                            <td>
                                                {{
                                                    Carbon::createFromFormat('Hi', $shcp->hour??false)
                                                    ->format('h:i a')
                                                }}
                                            </td>

                                            <td class="text-gray-800 px-3">
                                                {{ '|' }}
                                            </td>

                                            <td class="">
                                                {{
                                                    $shcp->in_out == 'I'? 'In' : 'Out'
                                                }}
                                            </td>

                                        </tr>
                                    @endforeach

                                @endif
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <!-------------3rd------->
            <div class="py-6">
                <div class="">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-6 ">
                        <div class="px-1 text-gray-900 dark:text-white">
                            @if ($employee->username??false)
                                <img src="images/{{$employee->username}}.jpg" alt="png" width="400" height="400">
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </div>
    </x-slot>

</x-div-grid>

