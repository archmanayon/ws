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

                            <div class="flex gap-2">
                                <div class="mt-4" >
                                    <x-input-label for="start_date" :value="__('Start Date')" />
                                    <x-text-input  id="start_date" class="block mt-1" type="date" name="start_date" :value="request('start_date')" required autofocus autocomplete="start_date" />
                                    <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                </div>
                                <div class="mt-4">
                                    <x-input-label for="end_date" :value="__('End Date')" />
                                    <x-text-input id="end_date" class="block mt-1" type="date" name="end_date" :value="request('end_date')" required autofocus autocomplete="end_date" />
                                    <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                                </div>
                                <div >
                                    <button
                                      type="submit"
                                      name="submit_indi"
                                      value=""
                                      class="mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm p-2 text-base"
                                    >
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </table>

                </div>

                <div class="grid grid-cols-2">
                    {{-- 1st column --}}
                    <div class="p-6 text-gray-900 dark:text-gray-100 border">
                        {{-- <p class="text-lg text-center font-bold m-5">Dark Table Design</p> --}}
                            <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                                <tr class="border text-left border-b border-gray-300">
                                    <th class="border-r px-4 py-3">Raw Bio Text Files
                                    </th>
                                    <th class="">Updated Bio Text Files
                                    </th>

                                </tr>

                                @foreach ( $mappedUser as $each_user => $daily)

                                    @foreach ( $daily as $daily)

                                        @if ($daily && $daily->punch->count() >= 5)

                                            <tr class="border bg-gray-700 border-b border-gray-600">

                                                @if ($daily->updated_bio[0]->date??false )

                                                    {{-- for first table column --}}
                                                    <td class="border-r dark:text-gray-400 py-3 text-xs">

                                                        {{ $daily->user->name." = ".$daily->punch->count()."| ".
                                                        $daily->punch[0]->date}}

                                                    </td>

                                                    {{-- for second table column --}}
                                                    <td class="border-r dark:text-gray-400 py-3 text-xs">
                                                        {{ $daily->updated_bio[0]->name." = ".$daily->updated_bio->count()."| ".
                                                        $daily->updated_bio[0]->date}}
                                                    </td
                                                @else

                                                    {{-- for first table column --}}
                                                    <td class="border-r py-3">
                                                        {{ $daily->user->name." = ".$daily->punch->count()."| ".$daily->punch[0]->date}}

                                                        {{-- @if ($daily->updated_bio??false)
                                                            @foreach ( $daily->updated_bio as $index => $value )
                                                                {{ "\n".$index."||".$value->hour}}
                                                            @endforeach
                                                        @endif --}}


                                                    </td>

                                                    {{-- for second table column WITH 'HREF'--}}
                                                    <td class="border-r py-3">

                                                        <a href="rawbio/{{$daily->user->timecard.$daily->punch[0]->date}}" class="border-r dark:hover:text-white focus:outline focus:outline-2 focus:outline-red-500 focus:rounded-sm hover:text-gray-900 text-orange-300">
                                                            {{ $daily->user->name." = ".$daily->punch->count()}}
                                                        </a>
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
