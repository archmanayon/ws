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
                        <form method="POST" action="{{auth()->user()->username}}">
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
                                        
                            {{-- {{ $mappedArray[0]->subString_array['1']->hour ?? false}}<br> --}}
                            {{ $mappedArray[0]->subString_array ?? false}}

                            @foreach ( $mappedArray as $daily)                            

                                @if ( $daily ?? false)                                                              
                                
                                    <tr class="bg-gray-700 border-b border-gray-600">
                                        <td class="px-4 py-3">
                                            {{ $daily->user->student_id}}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $daily->user->name}}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $daily->date}}
                                        </td><td class="px-4 py-3">
                                            {{ $daily->type}}
                                        </td>
                                        </td><td class="px-4 py-3">
                                            {{ $daily->rendered}}
                                        </td>
                                        </td>
                                        @if ($daily->ws_double)
                                            </tr>
                                            <tr class="bg-gray-700 border-b border-gray-600">
                                                <td class="px-4 py-3">
                                                    {{ $daily->student_id}}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $daily->name }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ $daily->date }}
                                                </td><td class="px-4 py-3">
                                                    {{ 'UND' }}
                                                </td>
                                                </td><td class="px-4 py-3">
                                                    {{ $daily->ws_double }}
                                                </td>
                                                </td>
                                            </tr>
                                        @endif                                        
                                    </tr><br>
                                @endif                            
                                
                            @endforeach

                        </table>     
                   
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
