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
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                        <td>

                            <div class="text-6xl mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                {{ $date->format('h:i A') }}
                            </div>
                        </td>
                        <td>
                            {{ $date->format('F j, Y')  }}
                            
                        </td>
                       
                </div>
            </div>
            
        </div>  
       
    </div>



    <div class="py-12">
        <form method="POST" action="{{ route('punches') }}">
        @csrf         
            <div class="max-w-xs mx-auto sm:px-6 lg:px-8">
                                 

                    <div class="text-gray-900 dark:text-gray-100"> 
            
                        <x-responsive-nav-link :href="route('punches')"
                            class="text-6xl m-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                            focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Punch Now') }}
                        </x-responsive-nav-link>
                       
                                
                    </div>
                    
            </div>
        
        </form>
       
    </div> 

    <div class="py-12">
                                
                <table class="max-w-xl mx-auto sm:px-6 lg:px-8 p-6 text-gray-900 dark:text-gray-100" >
                   
                    <tr>
                        <th>
                            {{ '(today) ' }}
                        </th>
                    </tr>

                    @foreach ( $punches_today as $shcp)
                        <tr>
                            <td> 
                                {{ 
                                    $shcp->in_out
                                }}
                            </td>
                            <td> 
                                {{ 
                                    Carbon::createFromFormat('Hi', $shcp->hour??false)
                                    ->format('h:i a')
                                }}
                            </td>

                            
                            {{-- <td> 
                                {{ 
                                   $shcp->in_out
                                }}
                            </td> --}}
                        </tr>
                    @endforeach
                   
        
    </div>

</x-app-layout>


