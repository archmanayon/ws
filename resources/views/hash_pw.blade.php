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
    <form method="POST" action="hash_pw">

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        
                       @foreach ( $hashedPassword as $hashedPassword )
                           {{ $hashedPassword }} <br>
                       @endforeach
                       @foreach ($corrected_name as $corrected_name)
                           {{ $corrected_name }}<br>
                       @endforeach

                    </div>
                </div>
            </div>
        </div>         
    
    </form>    

</x-app-layout>


