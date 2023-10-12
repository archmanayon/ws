<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">


        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}<br>
                    <span class="text-xs">{{ auth()->user()->student_id }} </span>
                </div>

                <div class="justify-items-center lg:grid lg:grid-cols-2">

                        <div class="p-6 text-gray-900 dark:text-gray-100">
                                {{-- {{ 'Employee' }}<br> <br> --}}
                                @admin
                                        
                                        <a href="tardi_process" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Tardiness Processing
                                        </a><br>

                                        <a href="register" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Register an Employee
                                        </a><br>

                                        <a href="ip_reg" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Register IP Add
                                        </a><br>

                                        <a href="print" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Extract Abs
                                        </a><br>

                                        <a href="adea" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                View USC EMPLOYEE Absences
                                        </a><br>

                                        <a href="js" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Java Script Pratice
                                        </a><br>

                                        <a href="raw_bio_text" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                View Raw Biometric
                                        </a><br>

                                        <a href="text_files" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Biometric report (text files)
                                        </a><br>


                                        <a href="dtr" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                EMPLOYEES dtr
                                        </a><br>

                                        <a href="shcp" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Punch to Biometrics
                                        </a><br>

                                        <a href="setup" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                S-E-T U-P  -  E-V-E-R-Y-T-H-I-N-G
                                        </a><br>

                                        <a href="all_employee_tasks" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                ALL Employee Tasks
                                        </a><br>

                                        <a href="dept_list" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Department_List
                                        </a><br>

                                @endadmin                              

                                @ws
                                        <a href="report" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 m-5 focus:rounded-sm focus:outline-red-500">
                                                View My Absences
                                        </a><br>

                                        <a href="my_dtr" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 m-5  focus:rounded-sm focus:outline-red-500">
                                                View My DTR
                                        </a><br>

                                @endws

                                @staff
                                        {{-- <a href="task" class="text-2xl font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Submit Daily Task Report
                                        </a><br>                                       --}}

                                        <x-dropdown relative='x' align='top'>
                                       
                                                <table class="dark:bg-gray-800 dark:text-gray-200 m-0 max-w-xl mt-8 rounded-t-lg text-left text-xs">

                                                        @php
                
                                                        $count = 1;
                
                                                        @endphp                                               
                
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

                                                                                <x-slot name="trigger">
                                                                                        <button class="w-auto inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition ease-in-out duration-150">
                                                                                                <div>
                                                                                                        <td class="px-6">
                                                                                                                <span class="text-orange-500 text-sm"> Lates</span>
                                                                                                                {{$loop->count}}
                                                                                                        </td>
                                                                                                </div>
                                                                
                                                                                                <div class="ml-1">
                                                                                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                                                        </svg>
                                                                                                </div>
                                                                                        </button>
                                                                                
                
                                                                                        <td class="px-6">
                                                                                                <span class="text-orange-500 text-sm"> Tardiness: </span>
                                                                                                {{$tardi_desc->where('id', $sanction_s)->first()->tardiness??false}}
                                                                                        </td>

                                                                                        <td class="px-6 text-xl w-72">
                                                                                                <div>
                                                                                                        <a href="tardi" class=" font-semibold text-gray-600
                                                                                                                hover:text-gray-900 text-green-600 dark:text-green-500 dark:hover:text-white
                                                                                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                                                                                View Personal Tardiness Variance
                                                                                                        </a>
                                                                                                </div>
                                                                                        </td>
                                                                                </x-slot>                                                               
                                                                        </tr>                                                                     
                                                                                                                                
                                                                        @php
                                                                                $count++;
                                                                        @endphp
                
                                                                        </tr>
                
                                                                @endif                                                        
                
                                                        @endforeach  

                                                </table>

                                                <x-slot name="content" class="text-gray-900"> 
                                                            
                                                        <table class="text-xs">
                                                                <tr>                                                                
                                                                                <th class="px-2 py-3"> Date </th>
                                                                                <th class="px-2 py-3 text-red-400"> Pnch </th>
                                                                                <th class="px-2 py-3 text-green-400"> Ofcl </th>      
                                                                                <th class="px-2 py-3 text-red-400"> Pnch </th>
                                                                                <th class="px-2 py-3 text-green-400"> Ofcl </th>                                                                        
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
                                                                                <td class="px-4 py-3">
                                                                                        {{$daily->date}}
                                                                                </td>
                                                                                <td class="px-4 py-3 text-red-400">
                                                                                        {{$daily->punch->am_in}}
                                                                                </td>
                                                                                <td class="px-4 py-3 text-green-600">
                                                                                        {{$daily->official->am_in }}
                                                                                </td>
                                                                                <td class="px-4 py-3 text-red-400">
                                                                                        {{$daily->punch->pm_in}}
                                                                                </td>
                                                                                <td class="px-4 py-3 text-green-600">
                                                                                        {{$daily->official->pm_in }}
                                                                                </td>
                                                                        </tr>                                        
        
                                                                @endforeach
                                                        </table>                                                                              
    
                                                </x-slot> 
                                        </x-dropdown>

                                @endstaff

                                @head

                                        {{-- <a href="dept_head" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Endorse Tasks
                                        </a><br> --}}


                                        <a href="tardi_group" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                View Staff Tardiness Report
                                        </a><br> 

                                @endhead

                                @staffhead
                                        {{-- <a href="task" class="text-2xl font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Submit Daily Task Report
                                        </a><br> --}}

                                        <a href="report" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                View My Absences
                                        </a><br>

                                       

                                        <a href="tardi" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                View Personal Tardiness Variance
                                        </a><br>

                                        <a href="my_dtr" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                View My DTR
                                        </a><br>

                                @endstaffhead

                        {{-- @admin

                        <a href="hash_pw" class="font-semibold text-gray-600
                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                Hash</a>     <br>
                        @endadmin --}}

                        </div>

                        <div class="p-6 text-gray-900 dark:text-gray-100">

                                {{-- {{ 'Head Role' }}<br> <br> --}}

                                @admin
                                        <a href="dept_head" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        Endorse Tasks
                                        </a><br>


                                        <a href="tardi_group" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                View Staff Tardiness Report
                                        </a><br>
                                @endadmin

                                @staffhead

                                        <a href="dept_head" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                Endorse Tasks
                                        </a><br>


                                        <a href="tardi_group" class="font-semibold text-gray-600
                                                hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                                focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                View Staff Tardiness Report
                                        </a><br>

                                @endstaffhead
                        </div>

                </div>
            </div>
        </div>

        {{-- ________________________________ --}}


    </div>
</x-app-layout>
