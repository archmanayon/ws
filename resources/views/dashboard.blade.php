<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}<br>
                    {{ auth()->user()->username }}  

                    
                    
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @admin
                    <a href="all_absences" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            All WS Absences Report
                    </a> <br>
                    @endadmin
                    
                    <a href="report/{{ auth()->user()->username }}" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            My Absences</a><br>                         
                   
                    @admin
                    <a href="print" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            Extract Abs</a><br>                 
                    @endadmin

                    @admin
                    <a href="adea" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            USC EMPLOYEES</a>     <br>               
                    @endadmin

                   
                    <a href="js" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            Java Script Pratice</a>  
                    <br>
                     @admin
                    <a href="shcp" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            SHCP Biometrics Report</a>     <br>               
                    @endadmin
                    
                   
                                
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
