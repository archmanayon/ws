@php

    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use App\Models\Task;


@endphp

<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-12">
        <div class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            <h2>
                {{ 'Personnel Tardiness Variance Record' }}
            </h2>            
        </div>

        <div class="lg:grid lg:px-8 m-5 mx-6 sm:px-6">

            {{-- 1st column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{ $user->name??false }} <br>
                    {{ $user->head->department??false }}
                </div>

            </div>

            {{-- 2nd column --}}
            <div class="bg-white dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="bg-gray-800 rounded-t-lg text-sm w-full">

                        <thead class="border-b border-gray-300 text-left">

                            <th class="px-4 py-3 w-auto">{{ 'Reported Tardiness' }}</th>

                            <th class="px-4 py-3">{{ 'SY' }}</th>

                            <th class="px-4 py-3">{{ 'Month' }}</th>

                            <th class="px-4 py-3">{{ 'Total' }}</th>

                            <th class="px-4 py-3">{{ "Action Taken" }}</th>

                            <th class="px-4 py-3">{{ "Date" }}</th>

                            <th class="px-4 py-3 w-auto">{{ "Head's Remarks" }}</th>

                            <th class="px-4 py-3">{{ '' }}</th>


                        </thead>

                        @foreach ($user->tardis as $tardi)

                        <tr>
                            {{-- reported tardiness --}}
                            <td class="px-4 py-3 w-48">
                                {{ $tardi->tardi_description->tardiness }}
                            </td>

                            {{-- school year --}}
                            <td class="px-4 py-3 w-32">
                                {{ $tardi->term->school_year }}
                            </td>

                            {{-- month --}}
                            <td class="px-4 py-3 w-20">
                                {{ Carbon::create()->month($tardi->month)->format('F')}}
                            </td>

                            {{-- total --}}
                            <td class="px-4 py-3 w-8">
                                {{ $tardi->total}}
                            </td>

                            {{-- action --}}
                            <td class="px-4 py-3 w-48">
                                {{ $tardi->tardi_description->action }}
                            </td>
                            {{-- date --}}
                            <td class="px-4 py-3 w-4">
                                {{  Carbon::parse($tardi->sig_date)->format('m/d/y')}}
                            </td>

                            {{-- remarks --}}
                            <td class="px-4 py-3 w-72 {{ !$tardi->conforme?'text-green-600':'' }} ">
                                {{ $tardi->remarks }}
                            </td>

                            <td class="px-4 py-3">
                            <form method="POST" action="{{route('post_tardi')}}" >
                                @csrf
                                {{-- only shows once head already made remarks --}}
                                @if (!$tardi->conforme)

                                    @if ($tardi->head_sig)
                                        <button class="text-orange-300" type="submit" name="conforme" value="{{$tardi->id}}">Conforme</button>
                                    @else
                                        <div class="text-red-300">
                                            {{ 'pls. remind head' }}
                                        </div>

                                    @endif

                                @else
                                    <button class="text-green-600" type="submit" name="conforme" value="{{$tardi->id}}">Open</button>
                                @endif
                            </form>

                            </td>

                        </tr>

                        @endforeach
                    </table>

                </div>

            </div>

            {{-- 3rd column --}}
            <div class="bg-white dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg w-20">

                <div class="p-3 text-gray-900 dark:text-gray-100">

                    <a href="dashboard" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            {{ 'HOME' }}
                    </a>

                </div>

            </div>

        </div>

</x-app-layout>


