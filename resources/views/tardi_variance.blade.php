@php

    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use App\Models\Task;


@endphp

<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-2">

        <div class="lg:grid lg:px-8 m-5 mx-6 sm:px-6">

            {{-- 1st column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="py-6 pl-10 text-gray-900 dark:text-gray-100 text-lg">
                    <div class="border-b pb-3 w-96">
                        <h2>
                            {{ 'Personnel Tardiness Variance Record' }}
                        </h2>
                        <h3 >
                            {{ 'Academic Year: ' }}{{ $tardis->term->school_year }}
                        </h3>
        
                        <h4>
                            {{ 'From the month of ' }}{{ Carbon::create()->month($tardis->month)->format('F')}}
                        </h4>
                    </div>

                    <div class="pt-3">

                    {{ $tardis->user->name??false }} <br>
                    {{ $tardis->user->head->department??false }}

                    </div>
                </div>

            </div>

            {{-- 2nd column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="bg-gray-800 rounded-t-lg text-sm w-3/4">

                        {{-- total --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Total Number of Times Late' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $tardis->total??false }}
                            </td>
                        </tr>

                        {{-- tardiness --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Reported Tardiness' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $tardis->tardi_description->tardiness??false }}
                            </td>
                        </tr>

                        {{-- action to take --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Action to be taken' }}
                            </td>

                            <td class="px-4 py-3">
                                {{-- if signed by head, show --}}
                                {{ $tardis->tardi_description->action??false }}
                            </td>
                        </tr>

                        <tr class="px-4 py-3">

                            <td class="px-4 py-3">
                                {{ "Dept. Head's Remarks" }}
                            </td>

                            <td class="px-4 py-3 {{ !$tardis->conforme?'text-green-600':'' }}">
                                {{ $tardis->remarks??false}}
                            </td>
                            
                        </tr>

                    </table>

                </div>

            </div>

            {{-- 3rd --}}
            @if (!$tardis->conforme) 
                <div class="bg-white dark:bg-gray-800 m-5 overflow-hidden pl-8 shadow-sm sm:rounded-lg w-48">

                    <div class="p-3 text-gray-900 dark:text-gray-100">

                            <form method="POST" action="{{route('post_tardi_variance')}}" >
                            @csrf
                                <tr>
                                    <td class="px-4 py-3">
                                        <button class="text-orange-300" type="submit" name="tardis_id" value="{{ $tardis->id??false }}">Conforme</button>
                                    </td>
                                </tr>
                            </form>

                    </div>

                </div>
            @endif 

            {{-- 4th --}}
            <div class="bg-white dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg w-20">

                <div class="p-3 text-gray-900 dark:text-gray-100">

                    <a href="tardi" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            {{ 'Back' }}
                    </a>

                </div>

            </div>

        </div>

</x-app-layout>


