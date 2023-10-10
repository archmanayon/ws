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

                    <a href="{{ auth()->user()->username }}">
                        {{ auth()->user()->name }}
                    </a>

                    <div>
                
                        {{ $nice??false }}

                    </div>

                    <form method="POST" action="{{ route('show_all_emp_tardi') }}">
                    @csrf

                        <table>                            
                            <td>
                                <div class="mt-4" >
                                    <x-input-label for="start_date" :value="__('Start Date')" />
                                    <x-text-input  id="start_date" class="block mt-1" type="date" name="start_date" :value="request('start_date')" required autofocus autocomplete="start_date" />
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>
                            </td>
                            <td>
                                <div class="mt-4">
                                    <x-input-label for="end_date" :value="__('End Date')" />
                                    <x-text-input id="end_date" class="block mt-1" type="date" name="end_date" :value="request('end_date')" required autofocus autocomplete="end_date" />
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                </div>
                            </td>
                            <td>
                                <div class="mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <button type="submit" name="submit_indi" value="">
                                        Submit
                                    </button>
                                </div>
                            </td>                            
                        </table>

                    </form>

                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100 border">

                    <p class="text-lg text-center font-bold m-5">tardiness processing</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif                    

                    <form method="POST" action="{{ route('save_all_emp_tardi') }}">
                    @csrf

                        <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">                            

                            <tr class="text-left border-b border-gray-300">

                                <th class="px-4 py-3">Stud ID</th>
                                <th class="px-4 py-3">Term</th>
                                <th class="px-4 py-3">Month</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Desc</th>
                                <th class="px-4 py-3">Head ID</th>
                                <th class="px-4 py-3">Encoded tardis</th>

                            </tr>

                            @foreach ( $mappedUser as $key => $each_user)

                                @php

                                    $count = 1;

                                @endphp

                                @foreach (
                                    
                                        array_filter($each_user, function ($daily) {
                                            return is_object($daily) && 
                                                // property_exists($daily, 'type') &&
                                                // $daily->user->name == 'ONDE, MARK FRANCIS' &&
                                                $daily->type == 'LTE' ||

                                                is_object($daily) &&   
                                                // property_exists($daily, 'type_late') &&                                         
                                                // $daily->user->name == 'ONDE, MARK FRANCIS' &&
                                                $daily->type_late == 'LTE';
                                                // && $daily->late_count >= 10;
                                        })

                                    as $index => $daily)
                                    
                                    {{-- @if ( $daily->type == 'LTE'
                                        || $daily->type_late == 'LTE'
                                        || $daily->required_h_late > 0
                                    ) --}}

                                    @if( 
                                    $loop->last && $loop->count >= 10)
                                    {{-- // &&  
                                    // $loop->count >= 10) --}}
                                    
                                        <tr class="bg-gray-700 border-b border-gray-600">
                                                                                    
                                            <td class="px-4 py-3">
                                                {{$daily->user->name}}
                                                <input type="hidden" name="lte[{{ $daily->user->id.'_'.$loop->count }}][user_id]"
                                                value="{{ $daily->user->id }}">
                                            </td>

                                            <td class="px-4 py-3">
                                                {{ $term->school_year }}
                                                <input type="hidden" name="lte[{{$daily->user->id.'_'.$loop->count }}][term_id]"
                                                value="{{ $term->id }}">
                                            </td>
                                            

                                            <td class="px-4 py-3">
                                                {{ $daily->month->format('F') }}  
                                                <input type="hidden" name="lte[{{$daily->user->id.'_'.$loop->count }}][month]"
                                                value="{{$daily->month->format('n')}} ">
                                                {{-- $daily->month->format('mdy') .' |'.end($daily) --}}
                                                
                                                
                                            </td>

                                            <td class="px-4 py-3">
                                                {{ $loop->count}}
                                                <input type="hidden" name="lte[{{ $daily->user->id.'_'.$loop->count }}][total]"
                                                value="{{ $loop->count }}">
                                            </td>

                                            {{-- <td class="px-4 py-3">
                                                {{ $daily->late_count }}
                                            </td> --}}

                                            <td class="px-4 py-3">

                                                @php
                                                $sanction_s = null;
                                                    
                                                    $loop->count >=10 && $loop->count < 17 ? $sanction_s = 1: (
                                                        $loop->count >=17 && $loop->count < 24 ?
                                                        $sanction_s =2:(
                                                            $loop->count >=24 && $loop->count < 31 ?
                                                            $sanction_s =3:(
                                                                $loop->count >=31 && $loop->count < 38 ?
                                                                $sanction_s =4:(
                                                                    $loop->count >=38 && $loop->count < 45 ?
                                                                    $sanction_s =5:(
                                                                        $loop->count >=45 && $loop->count < 52 ?
                                                                        $sanction_s =6:(
                                                                            $loop->count >=52 && $loop->count < 60 ?
                                                                            $sanction_s =7:(
                                                                                $loop->count >=60 && $loop->count > 10 ?
                                                                                $sanction_s =8: null                                                                 
                                                                            )
                                                                        )    
                                                                    )                                                                    
                                                                )
                                                            )
                                                        )
                                                    )                                               
                                               
                                                @endphp
                                                
                                                {{ $tardi_desc->where('id', $sanction_s)->first()->tardiness??false }}
                                                <input type="hidden" name="lte[{{ $daily->user->id.'_'.$loop->count }}][tardi_description_id]"
                                                value="{{ $sanction_s??false }}">
                                            
                                            </td>
                                            
                                            <td class="px-4 py-3">

                                                {{ $daily->user->head->user->name??false}}
                                                <input type="hidden" name="lte[{{ $daily->user->id.'_'.$loop->count }}][head_id]"
                                                value="{{ $daily->user->head_id }}">

                                                <input type="hidden" name="lte[{{ $daily->user->id.'_'.$loop->count }}][usertardidesc]"
                                                value="{{ $daily->user->username.'_'.$tardi_desc->where('id', $sanction_s)->first()->id??false }}">                                               

                                            </td>

                                            <td class="px-4 py-3">
                                                {{ $daily->user->tardis->first()->usertardidesc??false }}
                                            </td>
                                            
                                            @php
                                                $count++;
                                            @endphp

                                        </tr>

                                    @endif                                   

                                @endforeach

                            @endforeach

                        </table> 

                        <div class="mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                            focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <button type="submit" name="save_tardi" value="save_tardi">
                                Save all tariness record for this month
                            </button>
                        </div>

                    </form>

                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
