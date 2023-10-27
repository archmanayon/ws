@php

    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use App\Models\Task;


@endphp

<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-2">        

        <div class="lg:grid lg:px-8 m-5 mx-6 sm:px-6">

            {{-- 1st column --}}
            <div class="m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="py-6 pl-10 text-lg text-gray-900 dark:text-gray-100">

                    <div class="border-b pb-3 w-96">
                        <h2>
                            {{ 'Personnel Tardiness Variance Record' }}
                        </h2>
                        <h3 class ="text-xs">
                            {{ 'Academic Year: ' }}{{ $tardis->term->school_year }}
                        </h3>
        
                        <h4 class ="text-xs">
                            {{ 'Month:' }}{{ Carbon::create()->month($tardis->month)->format('F')}}
                        </h4>
                    </div>

                    <div class="pt-3">

                        {{ $tardis->user->name??false }} <br>

                        <span class ="text-xs">{{ $tardis->user->head->department??false }} </span>

                    </div>

                </div>

            </div>

            {{-- 2nd column --}}
            <div class="m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="dark:bg-gray-800 rounded-t-lg text-sm w-3/4">

                        {{-- total --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Total Number of Times Late' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $tardis->total??false }}
                            </td>
                        </tr>

                        {{-- tardiness --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Reported Tardiness' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $tardis->tardi_description->tardiness??false }}
                            </td>
                        </tr>

                        {{-- action to take --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Action to be taken' }}
                            </td>

                            <td class="px-4 py-3">
                                {{-- if signed by head, show --}}
                                {{ $tardis->tardi_description->action??false }}
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

            {{-- 3rd --}}
            <div class="dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-3 text-gray-900 dark:text-gray-100">

                    @if (!$tardis->conforme)

                        <form method="POST" action="{{route('post_tardi_group')}}" >
                            @csrf

                            <tr class="px-4 py-3">

                                <textarea class="dark:bg-gray-800 border-gray-700 h-20 rounded text-gray-200 text-sm w-3/4"
                                        id="" name="h_remarks" placeholder="{{ 'dept. head remarks...' }}"></textarea>
                            </tr><br>
                            <tr>
                                <input type="hidden" name="head_email" value="{{ $tardis->head->user_id }}">

                                <button class="border border-gray-400 font-sans rounded-md text-base w-20" type="submit" name="post_address" value="{{ $tardis->id??false }}">Submit</button>

                            </tr>

                        </form>

                    @endif

                </div>

            </div>

            {{-- 4rd --}}

            <div class="dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-3 text-gray-900 dark:text-gray-100">
                    <x-dropdown relative='x' align='top' width='0'>
                                       
                        <table class="dark:bg-gray-800 dark:text-gray-200 m-0 max-w-xl mt-8 rounded-t-lg text-left text-xs">

                                @php                
                                        $count = 1;                
                                @endphp    
                                
                                <x-slot name="trigger">                
                                        @foreach (                                                                
                                                array_filter($mappedUser, function ($daily) {
                                                return is_object($daily) && 
                                                        // property_exists($daily, 'type') &&
                                                        // $daily->user->name == 'ONDE, MARK FRANCIS' &&
                                                        $daily->type == 'LTE' ||

                                                        is_object($daily) &&   
                                                        // property_exists($daily, 'type_late') &&                                         
                                                        // $daily->user->name == 'ONDE, MARK FRANCIS' &&
                                                        $daily->type_late == 'LTE';
                                                        // && $daily->late_count >= 10;
                                                }) as $index => $daily)
                                        
                                                @if( 
                                                $loop->last && $loop->count >= 10)                                                                                                
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
                                                        
                                                        <tr class="px-2">

                                                                <td class="px-6 text-xl w-72">
                                                                        <button class="border border-transparent dark:bg-gray-800 dark:hover:text-gray-100 dark:text-gray-400 duration-150 ease-in-out focus:outline-none font-medium hover:text-gray-700 inline-flex items-center leading-4 px-8 rounded-md text-gray-500 text-sm transition">
                                                                                <div>
                                                                                        <td class="px-5">
                                                                                                <span class="text-orange-500 text-sm">View Tardiness Details  </span>                                                                                                
                                                                                        </td>
                                                                                </div>
                                                
                                                                                <div class="ml-1">
                                                                                        <svg class="bg-red-800 fill-current h-4 h-6 text-green-400 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                                        </svg>
                                                                                </div>
                                                                                <div>
                                                                                    <td class="px-5">
                                                                                        <span >{{ "with" }}</span>
                                                                                            {{'total of '.$loop->count.' lates'}}
                                                                                    </td>
                                                                            </div>
                                                                        </button>
                                                                </td>                                                                                                                          
                                                        </tr>                                                                     
                                                                                                                
                                                        @php
                                                                $count++;
                                                        @endphp                        
                                                @endif                                                        

                                        @endforeach 
                                </x-slot> 

                        </table>

                        <x-slot name="content" > 
                                <div class="w-96 max-h-40 overflow-y-auto">
                                    
                                        <table class="dark:bg-[#1F2937] text-xs">
                                                <tr>                                                                
                                                                <td class=" w-16"> Date </td>
                                                                <td class=" text-red-600 w-16"> am-Pnch </td>
                                                                <td class=" text-green-600 w-16"> Official </td>      
                                                                <td class=" text-red-600 w-16"> pm-Pnch </td>
                                                                <td class=" text-green-600 w-16"> Official </td> 
                                                                <td class=" text-orange-400 w-16"> Minutes </td>                                                                       
                                                </tr>
                                                <tr>
                                                    <td>{{ "--" }}</td>
                                                </tr>

                                                @foreach ( array_filter($mappedUser, function ($daily) {
                                                        return is_object($daily) && 
                                                                // property_exists($daily, 'type') &&
                                                                // $daily->user->name == 'ONDE, MARK FRANCIS' &&
                                                                $daily->type == 'LTE' ||

                                                                is_object($daily) &&   
                                                                // property_exists($daily, 'type_late') &&                                         
                                                                // $daily->user->name == 'ONDE, MARK FRANCIS' &&
                                                                $daily->type_late == 'LTE';
                                                                // && $daily->late_count >= 10;
                                                        }) as $index => $daily) 

                                                        <tr>
                                                                <td class="">
                                                                        {{$daily->date}}
                                                                </td>
                                                                <td class="pl-4 text-red-700">
                                                                        {{$daily->punch->am_in}}
                                                                </td>
                                                                <td class=" text-green-700">
                                                                        {{$daily->official->am_in }}
                                                                </td>
                                                                <td class="pl-2 text-red-300">
                                                                        {{$daily->punch->pm_in}}
                                                                </td>
                                                                <td class="text-green-300">
                                                                        {{$daily->official->pm_in }}
                                                                </td>
                                                                {{-- <td class="text-orange-600">
                                                                    {{$daily->late.' | '.round($daily->late * 60);}}
                                                                </td> --}}
                                                                <td class="text-orange-600">
                                                                    {{round($daily->late * 60).' min/s'}}
                                                                </td>
                                                        </tr>                                        

                                                @endforeach
                                        </table>
                                </div>

                        </x-slot> 
                </x-dropdown>
                </div>

            </div>


            {{-- nth --}}

                <a href="tardi_group" class="dark:bg-gray-800 dark:hover:text-white dark:text-gray-400 focus:outline focus:outline-2 focus:outline-red-500 focus:rounded-sm font-semibold h-8 hover:text-gray-900 m-5 overflow-hidden shadow-sm sm:rounded-lg text-center text-gray-600 text-xl w-20">
                   {{ 'Back' }}
                </a>



        </div>

</x-app-layout>


