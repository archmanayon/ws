@php
    use Illuminate\Support\Str;
    use \Carbon\Carbon;
@endphp

<x-guest-layout>

    <div class="lg:grid lg:grid-cols-2">
        <!---------------first column------------------>
        <div class="py-6">
            <div class="lg:px-8 mx-6 sm:px-6 text-center">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-6 ">
                    <div class="px-1 text-gray-900 dark:text-gray-100">
                        <td>

                            <div id="m_clock"
                                class="text-5xl mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                >
                                {{$date_->format('h:i:s A') }}<br>

                                <!--Dayspedia.com widget-->
                                    {{-- <iframe width='259' height='164' style='padding:0!important;margin:0!important;border:none!important;background:none!important;background:transparent!important' marginheight='0' marginwidth='0' frameborder='0' scrolling='no' comment='/*defined*/' src='https://dayspedia.com/if/digit/?v=1&iframe=eyJ3LTEyIjp0cnVlLCJ3LTExIjpmYWxzZSwidy0xMyI6dHJ1ZSwidy0xNCI6dHJ1ZSwidy0xNSI6dHJ1ZSwidy0xMTAiOmZhbHNlLCJ3LXdpZHRoLTAiOnRydWUsInctd2lkdGgtMSI6ZmFsc2UsInctd2lkdGgtMiI6ZmFsc2UsInctMTYiOiIxNnB4IDE2cHggMjRweCIsInctMTkiOiI0OCIsInctMTciOiIxNiIsInctMjEiOnRydWUsImJnaW1hZ2UiOi0xLCJiZ2ltYWdlU2V0IjpmYWxzZSwidy0yMWMwIjoiI2FmYmFjYiIsInctMCI6dHJ1ZSwidy0zIjp0cnVlLCJ3LTNjMCI6IiMzNDM0MzQiLCJ3LTNiMCI6IjQiLCJ3LTYiOiIjMzQzNDM0Iiwidy0yMCI6dHJ1ZSwidy00IjoiI2FmYmFjYiIsInctMTgiOnRydWUsInctd2lkdGgtMmMtMCI6IjMwMCIsInctMTE1IjpmYWxzZX0=&lang=en&cityid=7613'>
                                    </iframe> --}}
                                <!--Dayspedia.com widget ENDS-->

                            </div>
                            {{--
                                <div id=""
                                class="text-5xl mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                    focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    >
                                clock here
                                </div>
                            --}}


                        </td>

                        <form method="POST" action="{{ route('punches_') }}">
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
                        {{-- <td>
                            {{$date_->format('F j, Y')  }}

                        </td> --}}
                    </div>
                </div>
            </div>
        </div>

        <!---------------second column------------------>
        <div class="py-6">
            <div class="lg:px-8 mx-6 sm:px-6 text-center">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-6 ">
                    <div class="px-1 text-gray-900 dark:text-gray-100">
                        <table class="block dark:text-gray-100 p-6 py-1 sm:px-6 text-gray-900 w-auto" >
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
    </div>

</x-guest-layout>


