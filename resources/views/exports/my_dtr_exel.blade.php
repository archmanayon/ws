
<x-grid-layout>
    <style>

        table {
            margin-top: 10px;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black; /* Set border style */

            text-align: left;
            padding-left: 10px;
            padding-right: 10px;
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
    <div class="p-6 text-gray-900 dark:text-gray-100">
        {{ auth()->user()->name }}<br>
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

                        </tr>

                    @endif

                @endforeach

            @endif

        @endforeach

    </table>






</x-grid-layout>
