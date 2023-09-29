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
            
        </div>

        <div class="lg:grid lg:px-8 m-5 mx-6 sm:px-6">

            {{-- 1st column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{ $user->head->department??false }}
                   {{-- {{dd($group??false)}} --}}
                </div>

            </div>

            {{-- 2nd column --}}
            <div class="dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 dark:text-gray-100">

                    <table class="rounded-t-lg text-left text-sm w-full">

                        <thead class="border-b">

                            <th class="px-4 py-3">{{ 'Employee' }}</th>

                            <th class="px-4 py-3">{{ 'Reported Tardiness' }}</th>

                            <th class="px-4 py-3">{{ 'SY' }}</th>

                            <th class="px-4 py-3">{{ 'Month' }}</th>

                            <th class="px-4 py-3">{{ 'Total' }}</th>

                            <th class="px-4 py-3">{{ "Action Taken" }}</th>

                            <th class="px-4 py-3">{{ "Date" }}</th>

                            <th class="px-4 py-3">{{ "Head's Remarks" }}</th>

                            <th class="px-4 py-3">{{ '' }}</th>


                        </thead>
                        
                        @foreach($group as $dept)                            
                            
                            @foreach ( $dept->tardis as $tardi) 

                                {{-- -------------------this php line is for messaging only --}}
                                @php                                
                                    $names = explode(',', $tardi->user->name);
                                @endphp  
                                                           
                                <tr>

                                    {{-- user --}}
                                    <td class="px-4 py-3 w-48">
                                        {{ $tardi->user->name??false ."|". $tardi->total}}
                                    </td>

                                    {{-- reported tardiness --}}
                                    <td class="px-4 py-3 w-48">
                                        {{ $tardi->tardi_description->tardiness??false }}
                                    </td>

                                    {{-- school year --}}
                                    <td class="px-4 py-3 w-32">
                                        {{ $tardi->term->school_year??false }}
                                    </td>

                                    {{-- month --}}
                                    <td class="px-4 py-3 w-32">
                                        
                                        {{ Carbon::create()->month($tardi->month)->format('F')??false}}
                                    </td>

                                    {{-- total --}}
                                    <td class="px-4 py-3 w-8">
                                        {{ $tardi->total??false}}
                                    </td>

                                    {{-- action --}}
                                    <td class="px-4 py-3 w-40">
                                        {{ $tardi->tardi_description->action??false }}
                                    </td>
                                    {{-- date --}}
                                    <td class="px-4 py-3 w-4">
                                        @if ($tardi->sig_date != '0000-00-00 00:00:00')
                                            {{  Carbon::parse($tardi->sig_date)->format('m/d/y')}}
                                        @endif                                   
                                        
                                    </td>

                                    {{-- remarks --}}
                                    <td class="px-4 py-3 w-72">
                                        {{ $tardi->remarks }}
                                    </td>
                                    {{--head status --}}

                                    <td class=" py-3 {{ !$tardi->head_sig?'text-red-400':
                                                            (!$tardi->conforme?'text-red-300':'') }}">

                                        <form method="POST" action="{{route('staff_variance')}}" >
                                            @csrf
                                            {{-- only shows once head already made remarks --}}
                                            @if (!$tardi->conforme)

                                                @if ($tardi->head_sig??false)

                                                    {{'Pls remind'}} <br> {{ trim($names[1]) }}

                                                @else

                                                    <button type="submit" name="pre_address" value="{{$tardi->id}}">Pls click to Address</button>

                                                @endif

                                            @else

                                                {{ 'Addressed' }}

                                            @endif

                                        </form>

                                    </td>

                                </tr>
                            @endforeach

                        @endforeach
                    </table>

                </div>

            </div>

            {{-- 3rd column --}}
            {{-- <div class="bg-white dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg w-20">

                <div class="p-3 text-gray-900 dark:text-gray-100">

                    <a href="tardi_group" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            {{ 'Back' }}
                    </a>

                </div>

            </div> --}}

        </div>

</x-app-layout>


