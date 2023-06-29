@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use App\Models\Update_bio;

@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

        </h2>
    </x-slot>

    @php
        $bio_date = Carbon::createFromFormat('mdy', $str_date??false)
                    ->format('F j, Y');
        $bio_day = Carbon::parse($bio_date);
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:grid lg:grid-cols-2 lg:px-8 ">

            {{-- 1st column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">


                    <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                        <tr class="text-left border-b border-gray-300 ">
                            original
                            <th></th>
                            <th class="px-4 py-3">
                                {{  $bio_date.' | '. $bio_day->format('l') }}
                            </th>

                        </tr>

                        @foreach ($rawbio as $bio_punch)
                            <tr>    <td></td>
                                <td class="px-4 py-3">
                                    <div class="mt-2 block " >
                                        {{ $bio_punch->hour.$bio_punch->in_out }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>

            </div>

            {{-- 2nd column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">


                    <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                        <tr class="text-left border-b border-gray-300">
                            <th></th>
                            <th class="px-4 py-3">
                                {{  "DATE:".$bio_date }}
                            </th>
                        </tr>

                        
                    </table>

                </div>

            </div>

            {{-- 3rd column https://www.youtube.com/watch?v=S1yXIAjCbQw--}}

            

        </div>

</x-app-layout>


