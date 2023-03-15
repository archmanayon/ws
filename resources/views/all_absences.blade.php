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
                        {{ "All students" }}<br>
                        
                    </a>
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100 border">

                    @php
                            
                        $month = '02';

                        $year = '23';

                    @endphp   

                    <p class="text-lg text-center font-bold m-5">Dark Table Design</p>
                        <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                        <tr class="text-left border-b border-gray-300">
                            <th class="px-4 py-3">Student ID</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Type</th> 
                            <th class="px-4 py-3">Hours</th> 
                        </tr>      

                        @foreach ( $mappedArray as $calendar)                             

                            @foreach ( $calendar as $daily)

                                @if ( $daily ?? false)
                                    <tr class="bg-gray-700 border-b border-gray-600">
                                        <td class="px-4 py-3">
                                            {{ $daily->student_id}}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $daily->name}}
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
                                    </tr>
                                @endif                                    
                            @endforeach                                                              
                            
                        @endforeach     

                        
                    </table>
                    
                   
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
