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
            <div class="m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 dark:text-gray-100">
                    {{ 'Task Done' }}<br>
                    {{ $user->name??false }} <br>
                    {{ $user->head->department??false }}
                </div>

            </div>

            {{-- 2nd column --}}
            <div class="m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 text-gray-100 dark:text-gray-100">

                    <table class="bg-gray-800 rounded-t-lg text-sm">
                        <thead class="border-b border-gray-300 text-left">

                           <th class="px-4 py-3">{{ 'Staff' }}</th>


                            <th class="px-4 py-3">{{ 'Date' }}</th>

                            <th class="px-4 w-auto">{{ 'Tasks' }}</th>

                            <th class="px-3 py-3  w-auto">
                                {{ 'Remarks' }}
                            </th>

                            <th class="px-4 py-3 w-auto">{{ 'Status' }}</th>

                            <th class="px-4 py-3 w-auto">{{ 'Endorsed by' }}</th>
                        </thead>

                        @foreach ( $user->heads[0]->tasks->sortBy('status')->where('status', 0) as $current_task)
                        <tr class="border-b">

                            <form method="post" action="{{ route('endorse_task') }}">
                            @csrf

                                {{-- employee's --}}
                                <td class="px-4">
                                    {{ $current_task->user->name}}
                                </td>

                                {{-- date --}}
                                <td class="px-4">
                                    {{ $current_task->created_at}}
                                </td>

                                {{-- task --}}
                                <td class="px-4 sm:max-w-sm ">
                                    {{ $current_task->task_done }}
                                </td>

                                {{-- remarks --}}
                                <td class="w-auto">

                                    @if ($current_task->status)

                                        {{ $current_task->remarks }}

                                    @elseif ($current_task->remarks)

                                        <textarea class="bg-gray-800 border-gray-700 h-9 mt-2 rounded text-gray-200 text-sm"
                                            id="" name="head_remarks" placeholder="{{ $current_task->remarks?? 'type...' }}"></textarea>

                                    @else

                                        <textarea class="bg-gray-800 border-gray-700 h-9 mt-2 rounded text-gray-200 text-sm"
                                        id="" name="head_remarks" placeholder="type...">{{ $current_task->remarks?? false }}</textarea>

                                    @endif

                                </td>

                                {{-- status --}}
                                <td class="px-4 w-auto">


                                    @if ($current_task->status)

                                        {{ $current_task->status == 1 ? "Endorsed" :
                                            ($current_task->status == 2 ? "Disapproved" : "Pending")
                                        }}

                                        @else

                                            <select name="stat_option" id="task_stat" class="bg-gray-800 border-transparent mt-2 px-0 py-1 rounded text-1xl text-red-400 w-auto">
                                                <option class="text-gray-400" value="0">Pending</option>
                                                <option class="text-gray-400" value="1">Endorse</option>
                                                <option class="text-gray-400" value="2">Disapprove</option>
                                            </select>

                                    @endif

                                </td>

                                {{-- <td class="px-4 py-3">
                                    {{ $current_task->user->head->user->name }}
                                </td> --}}

                                {{-- <td class="px-4 py-3">
                                    {{ $current_task->head }}
                                </td> --}}

                                <td class="">

                                    @if (!$current_task->status)

                                        {{-- <button type="submit" name="task_id" value="{{ $current_task->id }}" class="">
                                            Edit
                                        </button>
                                    @else --}}

                                        {{-- <button type="submit" name="task_id" value="{{ $current_task->id }}" class="border-r border-t rounded-r-md ">
                                            Submit
                                        </button> --}}
                                        
                                        <x-primary-button class="m-auto-3"
                                        name="task_id" value="{{ $current_task->id }}">
                                            {{ __('Save') }}
                                            {{-- Punch
                                            {{ __( $in_out ? ($in_out == 'I'? 'In with your ID' :
                                                'Out with your ID') :
                                                'In with your ID') }} --}}
                                        </x-primary-button>

                                    @endif

                                </td>

                            </form>
                        </tr>
                        @endforeach

                    </table>


                </div>

            </div>

            {{-- 3rd column --}}
            <div class="bg-white dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg w-20">

                <div class="p-3 text-gray-900 dark:text-gray-100">

                    <a href="history_tasks" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            {{ 'History' }}
                    </a>

                </div>

            </div>

        </div>

</x-app-layout>


