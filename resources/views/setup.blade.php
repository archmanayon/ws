@php

    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use App\Models\Setup;


@endphp

<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-12">
        <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            <h2>
                {{ 'SETTING UP' }}
            </h2>
        </div>

        <div class="lg:grid lg:px-8 m-5 mx-6 sm:px-6">

            {{-- 1st column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{'setup'}}" >
                        @csrf
    
                            <table class="bg-gray-800 rounded-t-lg text-sm w-full">
    
                                <thead class="border-b border-gray-300 text-left">
    
                                    <th class="px-4 py-3 w-auto"></th>
                                   
                                    <th class="px-4 py-3"></th>
    
                                </thead>
                                
                                <tr>  
                                    
                                    <td>
                                        @php
                                        $options = [
                                            'StartPayroll'  => 'StartPayroll',
                                            'EndPayroll'    => 'EndPayroll',
                                            'Holiday'       => 'Holiday'
                                            ];
                                        @endphp
    
                                        <select name="type_source" class="border-transparent dark:bg-gray-700 dark:text-gray-300">
                                            <option value="{{ null }}">Select Type</option>
                                            @foreach ($options as $label => $value)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>     
                                        
                                    </td>
                                       
                                    <td class="mt-4">                                    
                                        <x-text-input  id="effective_date" class="block mt-1" type="date" name="effective_date" :value="request('effective_date')" required autofocus autocomplete="effective_date" />
                                        <x-input-error :messages="$errors->get('effective_date')" class="mt-2" />
                                    </td>
                                
                                    <td>
                                        <button type="submit" name="save_setup" value="save_setup">Save</button>
                                    </td>
                                </tr>                            
                               
                            </table>
                        </form>
                </div>

            </div>

            {{-- 2nd column --}}
            <div class="bg-white dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="bg-gray-800 rounded-t-lg text-sm w-full">
    
                        <thead class="border-b border-gray-300 text-left">

                            <th class="px-4 py-3 w-auto">{{ 'Type' }}</th>
                            
                            <th class="px-4 py-3">{{ 'Date' }}</th>

                        </thead>

                        @foreach ( $setup as $key =>$value)

                            <tr>
                                <td>
                                    {{ $value->type }}
                                </td>

                                <td>
                                    {{ $value->date }}
                                </td>
                            </tr>


                            
                        @endforeach
                                                
                               
                    </table>
                   

                </div>

            </div>

            {{-- 3rd column --}}
            <div class="bg-white dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg w-20">

                <div class="p-3 text-gray-900 dark:text-gray-100">

                    <a href="dashboard" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            {{ 'HOME' }}
                    </a>

                </div>

            </div>

        </div>

</x-app-layout>


