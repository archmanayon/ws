@php

    use Illuminate\Support\Str;    
    use Carbon\Carbon;    
    use App\Models\Task;


@endphp

<x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-12">
        <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            <h2>
                {{ 'Personnel Tardiness Variance Record' }}
            </h2>
            <h3 >
                {{ 'Academic Year 2022-2023' }}
            </h3>

            <h4>
                {{ 'From the month of March' }}
            </h4>
        </div>

        <div class="lg:grid lg:px-8 m-5 mx-6 sm:px-6">

            {{-- 1st column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                   
                    {{ $user->name??false }} <br>
                    {{ $user->head->department??false }}
                </div>

            </div>

            {{-- 2nd column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="bg-gray-800 rounded-t-lg text-sm w-3/4">

                        <thead class="border-b border-gray-300 text-left">

                            <th class="px-4 py-3">{{ 'Reported Tardiness' }}</th> 
 
                            <th class="px-4 py-3">{{ 'SY' }}</th>

                            <th class="px-4 py-3">{{ 'Month' }}</th>
 
                            <th class="px-4 py-3">{{ 'Total' }}</th> 

                            <th class="px-4 py-3">{{ "Head's Remarks" }}</th> 

                            <th class="px-4 py-3">{{ "Action Taken" }}</th>  

                            <th class="px-4 py-3">{{ "Date" }}</th>  

                            <th class="px-4 py-3">{{ 'Status' }}</th>

                            <th class="px-4 py-3">{{ 'Conforme' }}</th>
      
                            
                        </thead>
                        
                        @foreach ($user->tardis as $tardi)

                        <tr>
                            {{-- reported tardiness --}}
                            <td class="px-4 py-3">
                                {{ $tardi->tardi_description->tardiness }}
                            </td>

                            {{-- school year --}}
                            <td class="px-4 py-3">
                                {{ $tardi->term->school_year }}
                            </td>
                        
                            {{-- month --}}
                            <td class="px-4 py-3">
                                {{ Carbon::create()->month($tardi->month)->format('F')}} 
                            </td>

                            {{-- total --}}
                            <td class="px-4 py-3">
                                {{ $tardi->total}}
                            </td>

                            {{-- remarks --}}
                            <td class="px-4 py-3">
                                {{ $tardi->remarks }}
                            </td>

                            {{-- action --}}
                            <td class="px-4 py-3">
                                {{ $tardi->tardi_description->action }}
                            </td>
                            {{-- date --}}
                            <td class="px-4 py-3">
                                {{ $tardi->sig_date }}
                            </td>
                            {{-- status --}}
                            <td class="px-4 py-3">
                                {{ $tardi->conforme }}
                            </td>
                            
                            <td class="px-4 py-3">
                            <form method="POST" action="{{route('post_tardi')}}" >
                                @csrf
                                {{-- only shows once head already made remarks --}}
                                @if (!$tardi->conforme)
                                
                                    @if ($tardi->head_sig)
                                        <button type="submit" name="conforme" value="{{$tardi->id}}">Conforme</button>
                                    @else
                                        {{ 'pls. remind head' }}
                                    @endif

                                @else

                                    @if ($tardi->conforme)
                                        <button type="submit" name="conforme" value="{{$tardi->id}}">Open</button>
                                    
                                    @endif                                    

                                @endif   
                            </form>                             
                               
                            </td>
                            
                        </tr>

                        @endforeach
                    </table>

                </div>

            </div>

            {{-- 3rd column --}}
            <div class="bg-white dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg w-20">

                <div class="p-3 text-gray-900 dark:text-gray-100">

                    <a href="dept_head" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            {{ 'Back' }}
                    </a>
                    
                </div>

            </div>

        </div>

</x-app-layout>


