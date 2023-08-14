
<x-grid-layout>
    <style>

        .grid-container {
            display: block; /* Change to block layout for print */
        }

        .table-container {
            width: 30%; /* Adjust width for print layout and account for margin */
            float: left; 
            margin-right: 20px; 
            box-sizing: border-box;
            page-break-inside: avoid; /* Avoid breaking container across pages */
            border: 1px solid #ccc;
        }
        table {
            margin-top: 10px;
            border-collapse: collapse;
        }

        th, td {
            border: .25px solid black; /* Set border style */
            font-size: 12px;
            text-align: left;
            padding-left: 2px;
            padding-right: 2px;
            padding-top: 1px;
            padding-bottom: 1px;
            /* height: 1rem; */
        }       

        tr{
            height: 15px;
        }

        .usc_logo_container{
            margin-top: 2px;
            position: relative;
            background-size: cover;
            opacity: 0.25; /* Set the opacity to 50% */
        }

        .usc_img{
            position: absolute;
            top: 15px; /* Adjust the value to position from the top */
            left: 2px; /* Center the logo horizontally */
        }

        div{
            margin: 0px;
        }


    </style>

    <div class="usc_logo_container">
        <img class="usc_img" src="images/bblogo.png" alt="AiC" width="400" height="90">
    </div>
    <div class="dark:text-gray-100 font-sans text-gray-900 text-sm">
        {{ auth()->user()->name }}<br>
    </div>

    <div class="grid-container">

        <!---------------first column------------------>
        <div class="table-container">    

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

                </thead>

                @foreach ( $mapped_days as $daily)

                    @if ($daily && $loop->index <= 15)

                        @php $l = $loop->index; @endphp

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
                                        {{$l }}
                                    </td>

                                </tr>

                            @endif

                        @endforeach

                    @endif

                @endforeach

            </table>
        </div>

        <!---------------Second column------------------>
        <div class="table-container">    

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

                </thead>

                @foreach ( $mapped_days as $daily)

                    @if ($daily && $loop->index > 15 && $loop->index <= 30)

                        @php $l = $loop->index; @endphp

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
                                        {{$l }}
                                    </td>

                                </tr>

                            @endif

                        @endforeach

                    @endif

                @endforeach

            </table>
        </div>      

        <!---------------Third column------------------>
        <div class="table-container">    

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

                </thead>

                @foreach ( $mapped_days as $daily)

                    @if ($daily && $loop->index > 30 && $loop->index <=45)

                        @php $l = $loop->index; @endphp

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
                                        {{$l }}
                                    </td>

                                </tr>

                            @endif

                        @endforeach

                    @endif

                @endforeach

            </table>
        </div> 
    </div>






</x-grid-layout>
