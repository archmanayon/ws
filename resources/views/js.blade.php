@php
    use Illuminate\Support\Str;
    use \Carbon\Carbon; 
@endphp

<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ 'Dashboard' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" id="myElement">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    here   

                </div>

                <div id=""
                class="p-6 text-gray-900 dark:text-gray-100" >  

                    there                   
                    
                </div>

                <div id="m_clock"
                class="p-6 text-gray-900 dark:text-gray-100">  
                </div>

            </div>
        </div>        

</x-guest-layout>


