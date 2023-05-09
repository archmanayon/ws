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
                    {{-- <br>   
                    
                    @foreach ($bio as $bio)                       
                        {{$bio->hour}} <br>
                    @endforeach<br> --}}

                    <table>
                        <form method="POST" action="{{ route('print_post') }}">
                            @csrf    
                            
                            
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
                                <div class="mt-4">
                                    <x-input-label for="find_user" :value="__('FIND')" />
                                    <select placeholder ='trial'
                                        name="find_user" class="block mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" placeholder ="find user">
                                            <option >{{old('find_user')??'Search Employee' }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
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
        
                        </form>
                    </table>
                    
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100 border">  

                    <p class="text-lg text-center font-bold m-5">Dark Table Design</p>
                    
                        <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                            <tr class="text-left border-b border-gray-300">
                                <th class="px-4 py-3">Student ID</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Type</th> 
                                <th class="px-4 py-3">Hours</th> 
                            </tr>   
                            
                            {{-- {{ $update_bio->user->student_id}}<br> --}}

                            {{ $updated_bio_2 }}<br>

                            @foreach ($updated_bio_3 as $key => $value)

                               {{ $key.'|'.$value }} <br>
                            @endforeach
                           

                            @foreach ( $mappedUser as $daily)                                 

                                @if ( $daily)

                                    <tr class="bg-gray-700 border-b border-gray-600">
                                        <td class="px-4 py-3">
                                            {{ $daily->student_id}}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $daily->name}}
                                        <td>
                                            <x-dropdown >
                                                <x-slot name="trigger">
                                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition ease-in-out duration-150">
                                                        <div>{{ $daily->date }}</div>
                            
                                                        <div class="ml-1">
                                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                    </button>
                                                </x-slot>

                                                <x-slot name="content">
                                                    
                                                    @foreach ($daily->all_bio_punches as $bio)
                                                        
                                                        {{ $bio->hour.'~'.$bio->in_out }}
                                                        <div class="inline-block pl-8 flex-shrink-0"> <a href="update_bio/{{ $daily->timecard.$daily->bio_daily_array}}"> {{ 'update' }} </a></div>
                                                        <br>
                                                    @endforeach      
                                                    <div class="inline-block pl-8 flex-shrink-0">
                                                        <a href="update_bio/{{ $daily->timecard.$daily->bio_daily_array}}">                                                                
                                                            {{ $daily->all_bio_punches[0] ?? false ? '': 'no punch'}}    
                                                        </a>
                                                    </div>                                             

                                                </x-slot>
                                            </x-dropdown>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $daily->type}}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $daily->required_h}}
                                        </td>
                                        

                                        {{-- und outside abs --}}
                                        @if ($daily->ws_double)
                                            </tr>
                                            <tr class="bg-gray-700 border-b border-gray-600">
                                                <td class="px-4 py-3">
                                                    {{ $daily->student_id}}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $daily->name }}
                                                <td>
                                                    <x-dropdown>
                                                        <x-slot name="trigger">
                                                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition ease-in-out duration-150">
                                                                <div>{{ $daily->date }}</div>
                                    
                                                                <div class="ml-1">
                                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                    </svg>
                                                                </div>
                                                            </button>
                                                        </x-slot>
        
                                                        <x-slot name="content"> 
                                                            
                                                            @foreach ($daily->all_bio_punches as $bio)
                                                        
                                                                {{ $bio->hour.'~'.$bio->in_out }}
                                                                <div class="inline-block pl-8 flex-shrink-0"> <a href="update_bio/{{ $daily->timecard.$daily->bio_daily_array}}"> {{ 'update' }} </a></div>
                                                                <br>
                                                            @endforeach      
                                                            <div class="inline-block pl-8 flex-shrink-0">
                                                                <a href="update_bio/{{ $daily->timecard.$daily->bio_daily_array}}">                                                                
                                                                    {{ $daily->all_bio_punches[0] ?? false ? '': 'no punch'}}    
                                                                </a>
                                                            </div>      
        
                                                        </x-slot>
                                                    </x-dropdown>
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ 'UND' }}
                                                </td>                                               
                                                <td class="px-4 py-3">
                                                    {{ $daily->ws_double }}
                                                </td>
                                                
                                                
                                        @endif

                                    {{-- late with abs --}}
                                        @if ($daily->required_h_late > 0)
                                            </tr>
                                            <tr class="bg-gray-700 border-b border-gray-600">
                                                <td class="px-4 py-3">
                                                    {{ $daily->student_id}}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $daily->name }}
                                                </td>
                                                <td>
                                                    <x-dropdown>
                                                        <x-slot name="trigger">
                                                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition ease-in-out duration-150">
                                                                <div>{{ $daily->date }}</div>
                                    
                                                                <div class="ml-1">
                                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                    </svg>
                                                                </div>
                                                            </button>
                                                        </x-slot>
        
                                                        <x-slot name="content"> 
                                                            
                                                            @foreach ($daily->all_bio_punches as $bio)
                                                        
                                                                {{ $bio->hour.'~'.$bio->in_out }}
                                                                <div class="inline-block pl-8 flex-shrink-0"> <a href="update_bio/{{ $daily->timecard.$daily->bio_daily_array}}"> {{ 'update' }} </a></div>
                                                                <br>
                                                            @endforeach      
                                                            <div class="inline-block pl-8 flex-shrink-0">
                                                                <a href="update_bio/{{ $daily->timecard.$daily->bio_daily_array}}">                                                                
                                                                    {{ $daily->all_bio_punches[0] ?? false ? '': 'no punch'}}    
                                                                </a>
                                                            </div>      
        
                                                        </x-slot>
                                                    </x-dropdown>
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $daily->type_late }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $daily->required_h_late }}
                                                </td>
                                            
                                        @endif

                                        {{-- und with abs --}}
                                        @if ($daily->required_h_und > 0)
                                            </tr>
                                            <tr class="bg-gray-700 border-b border-gray-600">
                                                <td class="px-4 py-3">
                                                    {{ $daily->student_id}}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $daily->name }}
                                                </td>
                                                <td>
                                                    <x-dropdown>
                                                        <x-slot name="trigger">
                                                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-100 focus:outline-none transition ease-in-out duration-150">
                                                                <div>{{ $daily->date }}</div>
                                    
                                                                <div class="ml-1">
                                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                                    </svg>
                                                                </div>
                                                            </button>
                                                        </x-slot>
        
                                                        <x-slot name="content"> 
                                                            
                                                            @foreach ($daily->all_bio_punches as $bio)
                                                        
                                                                {{ $bio->hour.'~'.$bio->in_out }}
                                                                <div class="inline-block pl-8 flex-shrink-0"> <a href="update_bio/{{ $daily->timecard.$daily->bio_daily_array}}"> {{ 'update' }} </a></div>
                                                                <br>
                                                            @endforeach      
                                                            <div class="inline-block pl-8 flex-shrink-0">
                                                                <a href="update_bio/{{ $daily->timecard.$daily->bio_daily_array}}">                                                                
                                                                    {{ $daily->all_bio_punches[0] ?? false ? '': 'no punch'}}    
                                                                </a>
                                                            </div>      
        
                                                        </x-slot>
                                                    </x-dropdown>
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $daily->type_under }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $daily->required_h_und }}
                                                </td>
                                                                                            
                                        @endif                                       
                                    
                                    </tr>
                                @endif                            
                                
                            @endforeach                            
                      
                        </table>     
                   
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
