@php
    use Illuminate\Support\Str;
    use \Carbon\Carbon;
    use App\Models\User;
    use App\Models\Setup;
    

@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ 'Absences' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <a href="{{ auth()->user()->username }}">
                        {{ auth()->user()->name }} 

                    </a>
                    {{-- <br>   
                    
                    @foreach ($bio as $bio)                       
                        {{$bio->hour}} <br>
                    @endforeach<br> --}}                    
                            {{-- {{ dd($payroll_start) }} --}}
                    <table>
                        <form method="POST" action="{{ route('own_by_cal') }}">
                            @csrf    
                            
                            
                            <td>
                                <div class="mt-4" >
                                    <x-input-label class="ml-4" for="start_date" :value="__('Start Date')" />
                                    <input class="block mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    type="date" id="start_date" name="start_date" value="{{$payroll_start??false}}" required autofocus autocomplete="">
                                    
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>
                            </td>
                            <td>
                                <div class="mt-4">
                                    <x-input-label class="ml-4" for="end_date" :value="__('End Date')" />                                    
                                    <input class="block mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    type="date" id="end_date" name="end_date" value="{{$payroll_end??false}}" required autofocus autocomplete="">
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                </div>
                            </td>

                            <td>
                                <div class="mt-4">
                                    @php
                                        $searched_user = $users; 
                                    @endphp
                                    
                                </div>
                            </td>
    
                            <td>
                                <x-input-label class='py-2' for="submit_indi" :value="__('')" />    
                                <div class="mt-6 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                p-2 bg-gray-400 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    
                                    <button type="submit" name="submit_indi" value="">
                                        Submit
                                    </button>
                                </div>
                            </td>
        
                        </form>
                    </table>
                    
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100 border">  

                    <p class="text-lg text-center font-bold m-5"></p>
                    
                        <table class="rounded-t-lg m-5 w-5/6 mx-auto dark:bg-gray-800 dark:text-gray-200">
                            <tr class="text-left border-b dark:bg-gray-800">
                                <th class="px-4 py-3"></th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">Date</th>
                                <th class="px-4 py-3">Type</th> 
                                <th class="px-4 py-3">Hours</th> 
                            </tr>   
                            
                            @foreach ( $mappedUser as $daily)                                 

                                @if ( $daily)

                                    <tr class="dark:bg-gray-800 border-b dark:border-gray-600">
                                        
                                        <td class="px-4 py-3">
                                            {{-- {{ $daily->user->student_id}}  --}}
                                           
                                        </td>

                                        <td class="px-4 py-3">
                                            {{ $daily->user->name}}

                                        <td>
                                            <x-dropdown relative='x' align='top'>
                                                <x-slot name="trigger">
                                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md dark:text-gray-400 dark:bg-gray-800 dark:hover:text-gray-600 focus:outline-none transition ease-in-out duration-150">
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
                                                        
                                                        <div class="inline-block flex-shrink-0      dark:text-gray-400 text-gray-800"> {{ $bio->hour }}</div>
                                                        <div class="inline-block pl-3 flex-shrink-0 dark:text-gray-400 text-gray-800"> {{ $bio->in_out }}</div>  <br>                                                      
                                                       
                                                    @endforeach      
                                                    <div class="inline-block pl-8 flex-shrink-0 dark:text-gray-400 text-gray-800">
                                                                                                                   
                                                            {{ $daily->all_bio_punches[0] ?? false ? '': 'no punch'}}    
                                                       
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
                                        <tr class="dark:bg-gray-800 border-b dark:border-gray-600">
                                            <td class="px-4 py-3">
                                                {{-- {{ $daily->user->student_id}} --}}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $daily->user->name }}
                                            <td>
                                                <x-dropdown relative='x' align='top'>
                                                    <x-slot name="trigger">
                                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md dark:text-gray-400 dark:bg-gray-800 dark:hover:text-gray-600 focus:outline-none transition ease-in-out duration-150">
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
                                                    
                                                        <div class="inline-block flex-shrink-0      dark:text-gray-400 text-gray-800"> {{ $bio->hour }}</div>
                                                        <div class="inline-block pl-3 flex-shrink-0 dark:text-gray-400 text-gray-800"> {{ $bio->in_out }}</div>  <br>                                                             
                                                        @endforeach      
                                                        <div class="inline-block pl-8 flex-shrink-0 dark:text-gray-400 text-gray-800">
                                                            {{ $daily->all_bio_punches[0] ?? false ? '': 'no punch'}}                                                               
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
                                        <tr class="dark:bg-gray-800 border-b dark:border-gray-600">
                                            <td class="px-4 py-3">
                                                {{-- {{ $daily->user->student_id}} --}}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $daily->user->name }}
                                            </td>
                                            <td>
                                                <x-dropdown relative='x' align='top'>
                                                    <x-slot name="trigger">
                                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md dark:text-gray-400 dark:bg-gray-800 dark:hover:text-gray-600 focus:outline-none transition ease-in-out duration-150">
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
                                                    
                                                        <div class="inline-block flex-shrink-0      dark:text-gray-400 text-gray-800"> {{ $bio->hour }}</div>
                                                        <div class="inline-block pl-3 flex-shrink-0 dark:text-gray-400 text-gray-800"> {{ $bio->in_out }}</div>  
                                                            <br>
                                                        @endforeach      
                                                        <div class="inline-block pl-8 flex-shrink-0 dark:text-gray-400 text-gray-800">
                                                            {{ $daily->all_bio_punches[0] ?? false ? '': 'no punch'}}   
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
                                        <tr class="dark:bg-gray-800 border-b dark:border-gray-600">
                                            <td class="px-4 py-3">
                                                {{-- {{ $daily->user->student_id}} --}}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $daily->user->name }}
                                            </td>
                                            <td>
                                                <x-dropdown relative='x' align='top'>
                                                    <x-slot name="trigger">
                                                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md dark:text-gray-400 dark:bg-gray-800 dark:hover:text-gray-600 focus:outline-none transition ease-in-out duration-150">
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
                                                    
                                                        <div class="inline-block flex-shrink-0      dark:text-gray-400 text-gray-800"> {{ $bio->hour }}</div>
                                                        <div class="inline-block pl-3 flex-shrink-0 dark:text-gray-400 text-gray-800"> {{ $bio->in_out }}</div> 
                                                            <br>                                                        @endforeach      
                                                        <div class="inline-block pl-8 flex-shrink-0 dark:text-gray-400 text-gray-800">
                                                            {{ $daily->all_bio_punches[0] ?? false ? '': 'no punch'}}    
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
