@php

    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use App\Models\Task;


@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

        </h2>
    </x-slot>

    <div class="py-12">
        <div class="lg:grid lg:px-8 m-5 mx-6 sm:px-6">

            {{-- 1st column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ 'All Employee Daily Tasks ' }}<br>                   
                </div>

            </div>

            {{-- 2nd column --}}
            <div class="m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="dark:text-gray-100 p-6 dark:text-gray-100">

                    <table class="rounded-t-lg text-sm w-full">
                        <thead class="border-b border-gray-300 text-left">

                            <th class="px-4 py-3">{{ 'Staff' }}</th>

                            <th class="px-4 py-3">{{ 'Date' }}</th>

                            <th class="px-4 w-auto">{{ 'Tasks' }}</th>

                            {{-- <th class="px-3 py-3  w-auto">
                                {{ 'Remarks' }}
                            </th> --}}

                            <th class="px-4 py-3 w-auto">{{ 'Department' }}</th>

                            {{-- <th class="px-4 py-3">{{ 'Head' }}</th> --}}
                        </thead> 

                        @foreach ( Task::all()->sortBy('status') as $current_task)
                        <tr class="border-b">

                           

                                {{-- employee's --}}
                                <td class="px-4">
                                    {{ $current_task->user->name}}
                                </td>

                                {{-- date --}}
                                <td class="px-4">
                                    {{ $current_task->created_at->format('m/d/y') }}
                                </td>

                                {{-- task --}}
                                <td class="px-4 sm:max-w-sm ">
                                    {{ $current_task->task_done }}
                                </td>

                                {{-- remarks --}}
                                {{-- <td class="w-auto">

                                    {{ $current_task->remarks }}

                                </td> --}}

                                {{-- status --}}
                                <td class="px-4 w-auto">


                                    {{$current_task->user->head->department}}

                                </td>

                                {{-- <td class="px-4 py-3">
                                    {{ $current_task->user->head->user->name }}
                                </td> --}}

                                {{-- <td class="px-4 py-3">
                                    {{ $current_task->head }}
                                </td> --}}                              

                           
                        </tr>
                        @endforeach

                    </table>

                </div>

            </div>

            {{-- 3rd column --}}
            <div class="bg-white dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg w-20">

                <div class="p-3 text-gray-900 dark:text-gray-100">

                    <a href="dept_head" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            {{ 'Back' }}
                    </a>

                </div>

            </div>

        </div>

</x-app-layout>


