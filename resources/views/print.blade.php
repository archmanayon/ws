@php
    use Illuminate\Support\Str;
    use \Carbon\Carbon;


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
                        {{ "show individual" }}<br>

                    </a>
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100 border">
                    <table class="table-bardered">
                        <thead>
                            <th> Student ID</th>
                            <th> Name</th>
                            <th> Date</th>
                            <th> Type</th>
                            <th> Hours</th>
                        </thead>

                        @php
                            
                            $month = '02';

                        $year = '23';

                    @endphp   

                        @foreach ($collection_of_dates as $dates)  

                            <tr>
                                
                                @php 
                                    $formatted_date = Carbon::parse($dates);

                                    $dashed_date = $formatted_date->format('m-d-y');

                                    $day = $formatted_date->format('l');

                                    $sched_in = $day.'_in';
                                    $sched_out = $day.'_out';

                                    $official_in = $searched_user->schedule->$sched_in;
                                    $official_out = $searched_user->schedule->$sched_out;

                                    $official_num_hr = round((strtotime($official_out) - 
                                                            strtotime($official_in))/3600,2); 
                                                        
                                    $user_punch_in = $searched_user->punches->where('date', $formatted_date->format('mdy'))
                                                        ->pluck('in')->first();
                                    $user_punch_out = $searched_user->punches->where('date', $formatted_date->format('mdy'))
                                                        ->pluck('out')->first();
                                                        
                                    $Schedule_id =  $searched_user->manual_shift->where('date',$formatted_date->format('Y-m-d'))
                                                        ->pluck('schedule_id')->implode(', ');   
                                    
                                    $late = round((strtotime($user_punch_in) - 
                                                    strtotime($official_in))/3600,2);

                                    $under = round((strtotime($official_out) - 
                                                    strtotime($user_punch_out))/3600,2);
                                    
                                    $abs_type='trial abs';
                

                                    // $user_late = round((strtotime($user->punches->in) - 
                                    //             strtotime($user->schedule->$sched_in))/3600,2);
                                @endphp

                                @if (in_array($dashed_date, $holiday) || 
                                        $day =='Sunday' || 
                                        !isset($searched_user->schedule->$sched_in))  

                                    {{-- no absent--}}
                                    
                                @else

                                    @if( $searched_user->manual_shift->pluck('date')->contains( $formatted_date->format('Y-m-d')))
                                        @php
                                                                                      
                                            $official_in = $searched_user->schedule->find($Schedule_id)->Manual_in;
                                            $official_out = $searched_user->schedule->find($Schedule_id)->Manual_out;
                                        @endphp                                      
                                         
                                    @endif                                              

                                    @if ( $user_punch_in && $user_punch_out|| $late <  $official_num_hr && $under < $official_num_hr)  

                                    @else

                                        @php
                                            $abs_type = 'ABS';
                                        @endphp
                                        
                                    @endif  
                                    
                                    @if ($late > 0 && $under > 0)
                                        
                                        @php
                                            $abs_type = 'LTE and UND';
                                        @endphp

                                    @elseif ($late > 0 || $under > 0)

                                        @php
                                            
                                        @endphp

                                    @endif
                                   
                                    <td>{{ $searched_user->student_id}}</td>
                                    <td>{{ $searched_user->name}}</td>

                                    <td>{{ $formatted_date->format('m/d/y')}}</td>
                                    <td>{{ $abs_type}}</td> 
                                    <td>{{ $official_in }}-----</td>
                                    <td>{{ $official_out }}-----</td>

                                    <td>{{ $user_punch_in }}</td>
                                    <td>{{ $user_punch_out }}</td>
                                    <td>{{ $Schedule_id  }} </td>                                    
                                    
                                @endif
                                                                   
                            </tr>

                        @endforeach
                              
                        @foreach ($user as $user )                                                

                            @if($month != '0')  

                                @for( $date = 1; $date <= $num_days ; $date++ )                                           

                                    {{-- carbon format date --}}
                                    @php 

                                        $full = $month."-".$date."-".$year;
                        
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

                                                @foreach ($daily_punch as $punches)

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
