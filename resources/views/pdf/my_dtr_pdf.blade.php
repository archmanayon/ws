
<x-grid-layout>  
    <style>

        table {
            
            border-collapse: collapse;
            padding: 
        }
        
        th, td {
            border: 1px solid black; /* Set border style */
            
            text-align: left;
            padding: top right bottom left;
            }
    
    </style>

    <div class="p-6 text-gray-900 dark:text-gray-100">                    
        {{ auth()->user()->name }}
    </div>   

    <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
        <thead class="text-left border-b border-gray-300">

            <th class="px-4 py-3">Day
            </th>
            <th class="px-4 py-3">Date
            </th>
            <th class="px-4 py-3">Time
            </th>
            <th class="px-4 py-3">
            </th>
            <th class="px-4 py-3">Source
            </th>

        </thead>                           

        @foreach ( $mapped_days as $daily)

            @if ($daily)

                @foreach ($daily->orig_raw_bio as $punch)

                    @if ($punch)

                        <tr class="bg-gray-700 border-b border-gray-600">
                            <td class="px-4 py-3">
                                
                                {{ $daily->day}}
                            </td>
                            <td class="px-4 py-3">
                                {{ $daily->date }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $punch->hour }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $punch->in_out }}
                            </td>

                                <td class="px-4 py-3">
                                {{ $punch->punchtype->punchtype??false }}
                            </td>
                            
                        </tr>

                    @endif

                @endforeach

            @endif

        @endforeach        

    </table>

            

        
    
    
</x-grid-layout>
