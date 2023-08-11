<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/javaclock.js'])
        <div class="bg-gray-100 m-5 w-20 w-72">
            <img src="images/bblogo.png" alt="AiC" width="500" height="20">
        </div>
        <style>

            table {
                width: 100%; 
                border-collapse: collapse;
            }
            
            th, td {
                border: 1px solid black; /* Set border style */
                
                text-align: left;
                }
        
        </style>

        

    </head>     
    
    <body class="font-sans text-gray-900 antialiased">
        <div class="m-5 w-20 w-72">
            {{ auth()->user()->name }}
        </div>
        <div class="">
            
            <table class="bg-white m-5 px-1 rounded-t-lg w-auto">
                <thead class="bg-white text-gray-900">
                    <th class="px-2 text-xs">Day
                    </th>
                    <th class="px-2 text-xs">Date
                    </th>
                    <th class="px-2 text-xs">Time
                    </th>
                    <th class="px-2 text-xs">
                    </th>                    
    
                </thead>                           
    
                    @foreach ( $mapped_days as $daily)
    
                        @if ($daily)
    
                            @foreach ($daily->orig_raw_bio as $punch)
    
                                @if ($punch)
    
                                    <tr class="border-b border-gray-600 text-xs">
                                        <td class="w-4 px-2">
                                            
                                            {{ $daily->day}}
                                        </td>
                                        <td class="px-2">
                                            {{ $daily->date }}
                                        </td>
                                        <td class="px-2">
                                            {{ $punch->hour }}
                                        </td>
    
                                        <td class="px-2">
                                            {{ $punch->in_out }}
                                        </td>   
                                        
                                    </tr>
    
                                @endif
    
                            @endforeach
    
                        @endif
    
                    @endforeach     
    
            </table>
        </div>
    </body>
</html>