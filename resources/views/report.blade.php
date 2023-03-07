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
                    {{ $user->name.'  --------  Punches and Absences' }}
                </div>
                

                <div class="p-6 text-gray-900 dark:text-gray-100 border">
                    <table class="table-border-colapse">
                        <tr  class="border">
                            <th> calendar</th>
                            
                            <th> Date</th>
                            
                            <th> Day</th>
                            
                            <th> P_in</th>
                            
                            <th> P_out</th>
                            
                            <th> P_half</th>
                            <td></td>
                            
                            <th> Hrs</th> 
                            
                            <th> LTE</th>
                            
                            <th> UND</th> 
                        </tr>

                        @php

                            $holiday = array("01-01-23", "01-03-23", "01-04-23", "01-05-23", "01-06-23", "01-07-23");

                            $thirty_days = array('04', '06', '09','11');

                            $thirty_one = array('01', '03','05', '07', '08','10', '12');

                            $manual_shift; 

                            // $month_ = request('shift')<=9?'0'.request('shift'):request('shift');

                            $month_ = '01';

                            $year_ = '23';

                            $num_days = in_array($month_, $thirty_days) ? '30':
                                            (in_array($month_, $thirty_one)? '31':
                                                ($month_ =='0'? '0':'28')
                                            )
                                        ;                        
                        @endphp

                        {{-- {{ dd($user->name) }} --}}
                               
                        

                        @if($month_ != '0')  

                            @for( $date = 1; $date <= $num_days ; $date++ )   

                                @php

                                    $full = $month_."-".$date."-".$year_;
                    
                                    $full = Carbon::createFromFormat('m-d-Y', $full);      
                                    
                                    $daily_punch = $user->punches->where('date',$full->format('mdy'));

                                @endphp

                                <tr class="border">

                                    @if (in_array($full->format('m-d-y'), $holiday))                                       

                                        
                                        <td class="border">{{ $full->format('m-d-y')}}</td>
                                        
                                        <td  class="border">{{ "is_holiday" }}</td>
                                        <td class="border"> </td>
                                        <td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td>
                                        

                                    @elseif($full->format('l') =='Sunday')                                            

                                        <td>{{ $full->format('m-d-y')}}</td>
                                                                                  
                                        <td>{{ 'is_sunday' }}</td>
                                        <td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td>
                                        </tr>

                                    @else

                                        <td  class="border">{{ $full->format('m-d-y')}}</td>
                                                                                    
                                        @if( $daily_punch->contains('date',$full->format('mdy')))

                                            @foreach ($daily_punch as $punches)

                                                @php                                                

                                                    $in = $punches->day."_in";
                                                    $out = $punches->day."_out";
                                                    $half = $punches->day."_half";

                                                    $official_hours = round((strtotime($user->schedule->$out) - 
                                                                    strtotime($user->schedule->$in))/3600,2);

                                                    $late_s = round((strtotime($punches->in) - 
                                                                    strtotime($user->schedule->$in))/3600,2);

                                                    $under_t = round((strtotime($user->schedule->$out) - 
                                                                    strtotime($punches->out))/3600,2);
                                                    
                                                @endphp                                            
                                                
                                                <td class="border">{{  $punches->date}}</td> 
                                                
                                                <td class="border">{{  $punches->day}}</td>
                                                                                                
                                                <td class="border">{{  $punches->in}}</td> 
                                                  
                                                <td class="border">{{  $punches->out}}</td> 
                                                 
                                                <td class="border">{{  $punches->half}}</td>
                                                
                                                
                                                @php $manual_shift = 1; @endphp
                                                @if ($manual_shift?? false)
                                                    <td class="border">{{  Schedule::find($manual_shift)->$half.'-test'}}</td> 
                                                    
                                                    @else 
                                                    <td>{{  $user->schedule->$half}}</td>
                                                    
                                                @endif                                                     

                                                @if ( $punches->in && $punches->out
                                                    && $late_s < $official_hours && $under_t < $official_hours)
                                                    
                                                    <td class="border">{{round((strtotime($punches->out) - 
                                                                    strtotime($punches->in))/3600,2)
                                                        }}
                                                    </td>
                                                    
                                                    
                                                    <td class="border">
                                                        {{ $late_s > 0?$late_s:'' }}                                            
                                                    </td>
                                                    

                                                    <td class="border">
                                                        {{ $under_t > 0?$under_t:'' }}                                            
                                                    </td>
                                                    </tr>
                                                @else  <td class="border"> {{ 'absent' }}</td></tr>
                                                    {{-- @if ( round((strtotime($punches->out) - 
                                                                    strtotime($punches->in))/3600,2)
                                                                    )
                                                    @endif --}}

                                                @endif

                                            @endforeach  
                                        @else 
                                        
                                        <td> {{ 'absent'}}</td>
                                        <td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td><td class="border"> </td>
                                    </tr>
                                        @endif
                                        
                                    @endif
                                
                                
                            @endfor

                        @endif
                            
                                                           
                            
                        
                        <tr>
                            
                        </tr>
                    </table>
                    
                   
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
