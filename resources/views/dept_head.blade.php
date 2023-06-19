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
        <div class="max-w-7xl mx-auto sm:px-6 lg:grid lg:px-8 ">

            {{-- 1st column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-full">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ 'Task Done' }}<br>
                    {{ $user->name??false }} <br>
                    {{ $user->head->user->id??false }}
                </div>

            </div>

            {{-- 2nd column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-full">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                        <thead class="text-left border-b border-gray-300">
                            <th class="px-4 py-3 w-3/4">{{ 'Tasks' }}</th>
                            <th class="px-4 py-3">{{ 'Date' }}</th>
                            <th class="px-4 py-3">{{ 'Status' }}</th>
                            <th class="px-4 py-3 w-full">
                            {{-- {{ 'Remarks' }} --}}
                            </th>
                            {{-- <th class="px-4 py-3">{{ 'Head' }}</th> --}}
                        </thead>

                        @foreach ( $user->tasks as $current_task)
                        <tr class="border-b-2 sm:text-left">

                            <form method="post" action="{{ route('store_dept_head') }}">
                            @csrf

                                <td class="px-4 py-3">
                                    {{ $current_task->task_done }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $current_task->created_at->format('m/d/y') }}
                                </td>

                                <td class="px-4 py-3">

                                    <select name="option" id="task_stat" class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg text-sm w-auto border-transparent  sm:mx-auto">
                                        <option value="stat01">Endorse</option>
                                        <option value="stat02">Disapprove</option>
                                        <option value="stat03">HOLD</option>
                                    </select>

                                </td>

                                <td class="px-4 py-3">
                                    {{-- {{ $current_task->remarks }} --}}
                                    <textarea class="w-full rounded-t-lg m-5 mx-auto bg-gray-800 text-gray-200"
                                        id="" name="head_remarks" placeholder="Remarks"></textarea>
                                </td>

                                {{-- <td class="px-4 py-3">
                                    {{ $current_task->user->head->user->name }}
                                </td> --}}

                                {{-- <td class="px-4 py-3">
                                    {{ $current_task->head }}
                                </td> --}}

                                <td class="px-4 py-3">

                                    <button type="submit" name="endorse_task" value="endorse_task" class="ml-12 w-auto">
                                        Submit
                                    </button>

                                </td>

                            </form>
                        </tr>
                        @endforeach

                    </table>


                </div>

            </div>

        </div>

</x-app-layout>


