
    <div class="p-6 text-gray-900 dark:text-gray-100">
        {{ auth()->user()->name }}<br>
    </div>

    <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">

        <thead class="text-left border-b border-gray-300">

            <tr>

                <th class="px-4 py-3">Day
                </th>
                <th class="px-4 py-3">Date
                </th>
                <th class="px-4 py-3">Time
                </th>
                <th class="px-4 py-3">
                    
                </th>
            </tr>

        </thead>

        <tbody>

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

        </tbody>
        
    </table>
