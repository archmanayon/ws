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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <a href="{{ auth()->user()->username }}">
                        {{ auth()->user()->name }}
                    </a>
                    {{-- <br>

                    @foreach ($bio as $bio)
                        {{$bio->hour}} <br>
                    @endforeach<br> --}}

                    <table>
                        <form method="POST" action="{{ route('raw_bio_text_post') }}">
                            @csrf

                            <td>
                                <div class="mt-4" >
                                    <x-input-label for="start_date" :value="__('Start Date')" />
                                    <x-text-input  id="start_date" class="block mt-1" type="date" name="start_date" :value="request('start_date')" required autofocus autocomplete="start_date" />
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>
                            </td>
                            <td>
                                <div class="mt-4">
                                    <x-input-label for="end_date" :value="__('End Date')" />
                                    <x-text-input id="end_date" class="block mt-1" type="date" name="end_date" :value="request('end_date')" required autofocus autocomplete="end_date" />
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                </div>
                            </td>

                            <td>

                                <div class="mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <button type="submit" name="submit_indi" value="">
                                        Submit
                                    </button>
                                </div>
                            </td>

                        </form>
                    </table>

                </div>

                <div class="grid grid-cols-2">
                    {{-- 1st column --}}
                    <div class="p-6 text-gray-900 dark:text-gray-100 border">
                        {{-- <p class="text-lg text-center font-bold m-5">Dark Table Design</p> --}}
                            <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                                <tr class="text-left border-b border-gray-300">
                                    <th class="px-4 py-3">textfile
                                    </th>

                                </tr>

                                @foreach ( $mappedUser as $each_user => $daily)

                                    @foreach ( $daily as $daily)

                                        @if ($daily && $daily->punch->count()>=5)

                                            <tr class="bg-gray-700 border-b border-gray-600">

                                                @if ($daily->updated_bio[0]->date??false )

                                                    <td class="px-4 py-3">

                                                        {{ $daily->user->name." = ".$daily->punch->count()." | ".
                                                            $daily->user->date." = ".$daily->punch[0]->hour}}

                                                    </td>

                                                    <td class="px-4 py-3">
                                                        {{ $daily->updated_bio[0]->name." = ".$daily->updated_bio->count()." | ".
                                                            $daily->updated_bio[0]->date." = ".$daily->updated_bio[0]->hour}}
                                                    </td
                                                @else

                                                    <td>

                                                        <a href="rawbio/{{$daily->user->timecard.$daily->punch[0]->date}}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                                            {{ $daily->user->name." = ".$daily->punch->count()." | ".$daily->user->timecard.$daily->punch[0]->date}}

                                                        </a>
                                                    </td>

                                                @endif

                                                @if ($daily->updated_bio??false)
                                                    <td class="px-4 py-3">
                                                        {{ $daily->updated_bio[0]->date??false }}
                                                    </td>
                                                @endif

                                            </tr>

                                            {{-- @foreach ($daily->punch as $punch)

                                                @if ($punch)

                                                    <tr class="bg-gray-700 border-b border-gray-600">
                                                        <td class="px-4 py-3">
                                                            {{$punch->timecard." | ". $punch->date." | ". $punch->text}}
                                                        </td>
                                                    </tr>

                                                @endif

                                            @endforeach --}}

                                        @endif

                                    @endforeach

                                @endforeach

                            </table>

                    </div>

                    {{-- 2nd column --}}
                    <div class="p-6 text-gray-900 dark:text-gray-100 border">

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
