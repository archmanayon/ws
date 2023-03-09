@php
    use Illuminate\Support\Str;
    use \Carbon\Carbon;
    use App\Models\Schedule;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ 'Dashboard' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="report/{{ auth()->user()->username }}">
                        {{ "show individual" }}
                    </a>
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100 border">
                    <table class="table-bardered">
                        <tr>
                            <th> Student ID</th>
                            <th> Name</th>
                            <th> Date</th>
                            <th> Type</th>
                            <th> Hours</th>
                            {{-- <th> Day</th>

                            <th> P_in</th>
                            <th> P_out</th>
                            <th> P_half</th>
                            <th> Sked_in</th>
                            <th> Sked_out</th>
                            <th> Sked_half</th>

                            <th> LTE</th>
                            <th> UND</th>  --}}
                        </tr>

                        @php

                            $holiday = array("01-01-23", "01-02-23","01-03-23",
                                                "01-04-23","01-05-23","01-06-23",
                                                "01-07-23","01-16-23",
                                                "02-24-23", "02-25-23");

                            $thirty_days = array('04', '06', '09','11');

                            $thirty_one = array('01', '03','05', '07', '08','10', '12');

                            $manual_shift;

                            // $month_ = request('shift')<=9?'0'.request('shift'):request('shift');

                            $month_ = '02';

                            $year_ = '23';

                            // $num_days = in_array($month_, $thirty_days) ? '30':
                            // (in_array($month_, $thirty_one)? '31':
                            //     ($month_ =='0'? '0':'28')
                            // );

                            $create_num_days = Carbon::createFromDate($year_, $month_, 1);
                            $num_days = $create_num_days->daysInMonth;

                        @endphp

                        @foreach ($user as $user )

                            @if($month_ != '0')

                                @for( $date = 1; $date <= $num_days ; $date++ )

                                    {{-- carbon format date --}}
                                    @php

                                        $full = $month_."-".$date."-".$year_;

                                        $full = Carbon::createFromFormat('m-d-Y', $full);

                                        $daily_punch = $user->punches->where('date',$full->format('mdy'));

                                        $in = $full->format('l')."_in";
                                        $out = $full->format('l')."_out";
                                        $half = $full->format('l')."_half";

                                        $from_user_sched_in = $user->schedule->$in;
                                        $from_user_sched_out= $user->schedule->$out;

                                        $official_hours = round((strtotime($from_user_sched_out) -
                                                            strtotime($from_user_sched_in))/3600,2);

                                    @endphp

                                    @foreach ($user->manual_shift as $manual )

                                        @php
                                            $shift_daily = Carbon::createFromFormat('Y-m-d', $manual->date);
                                            $shift_daily = $shift_daily->format('m-d-y');
                                        @endphp

                                        @if ($shift_daily ==  $full->format('m-d-y'))
{{--
                                            {{ $manual->user->name.'|'.$shift_daily.'|'.$manual->schedule->Manual_in }}<br> --}}
                                            @php

                                                $from_user_sched_in = $manual->schedule->Manual_in;
                                                $from_user_sched_out= $manual->schedule->Manual_out;

                                                $official_hours = round((strtotime($from_user_sched_out) -
                                                                strtotime($from_user_sched_in))/3600,2);

                                            @endphp

                                        @endif

                                    @endforeach

                                    <tr>

                                        @if (in_array($full->format('m-d-y'), $holiday))
                                            </tr>
                                        @elseif($full->format('l') =='Sunday')
                                            </tr>

                                        @else

                                            @if( $daily_punch->contains('date',$full->format('mdy')))

                                                @foreach ($daily_punch->where('date',$full->format('mdy'))
                                                    as $punches)

                                                    @php

                                                        $late_s = round((strtotime($punches->in) -
                                                                        strtotime($from_user_sched_in))/3600,2);

                                                        $under_t = round((strtotime($from_user_sched_out) -
                                                                        strtotime($punches->out))/3600,2);

                                                    @endphp

                                                    @if ( $punches->in && $punches->out
                                                        && $late_s < $official_hours && $under_t < $official_hours)

                                                        @if($late_s > 0 && $under_t > 0)
                                                            {{-- if naa late and und... display una late dayon und --}}

                                                            <td>{{ $user->student_id }}</td>

                                                            <td><a href="report/{{ $user->username }}">
                                                                    {{ $user->name }}
                                                                </a>
                                                            </td>

                                                            <td>{{ $full->format('m/d/y')}}</td>

                                                            {{-- if naa late and und... display una late dayon und --}}
                                                            <td>{{ 'LTE'}}</td>
                                                            <td>{{ $late_s }}</td> </tr>
                                                            {{-- if naa late and und... display una late dayon und --}}
                                                            <tr>
                                                                <td>{{ $user->student_id }}</td>
                                                                <td>{{ $user->name }}</td>
                                                                <td>{{ $full->format('m/d/y')}}</td>
                                                                <td>{{ 'UND'}}</td>
                                                                <td>{{ $under_t > 0?$under_t:'' }}   </td>
                                                            </tr>

                                                        @elseif ($late_s > 0 || $under_t > 0)

                                                            <td>{{ $user->student_id }}</td>

                                                            <td><a href="report/{{ $user->username }}">
                                                                    {{ $user->name }}
                                                                </a>
                                                            </td>

                                                            <td>{{ $full->format('m/d/y')}}</td>

                                                            </td>


                                                            <td> {{ $late_s > 0?'LTE':
                                                                ($under_t > 0?"UND":'')
                                                                }}
                                                            </td>
                                                            <td> {{ $late_s > 0?$late_s:
                                                                ($under_t > 0?$under_t:'')
                                                                }}
                                                            </td></tr>

                                                        @endif

                                                    @else

                                                        <td>{{ $user->student_id }}</td>

                                                        <td><a href="report/{{ $user->username }}">
                                                                {{ $user->name }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $full->format('m/d/y')}}</td>
                                                        <td> {{ 'ABS' }}</td>
                                                        <td> {{ $official_hours }}</td>
                                                        </tr>

                                                        {{-- @if ( round((strtotime($punches->out) -
                                                                        strtotime($punches->in))/3600,2)
                                                                        )
                                                        @endif --}}

                                                    @endif

                                                @endforeach
                                            @else
                                            <td>{{ $user->student_id }}</td>
                                            <td> <a href="report/{{ $user->username }}">{{ $user->name }}</a></td>
                                            <td>{{ $full->format('m/d/y') }}</td>
                                            <td>{{ 'ABS' }}</td>
                                            <td>{{ $official_hours }}</td>
                                        </tr>
                                            @endif

                                        @endif


                                @endfor

                            @endif



                        @endforeach
                        <tr>
                            <td></td>
                        </tr>
                    </table>


                </div>

            </div>
        </div>
    </div>
</x-app-layout>
