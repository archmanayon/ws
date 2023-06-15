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
                    <form method="post" action="{{ route('store_task') }}">
                        @csrf
                        <textarea class="w-full rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200"
                           id="" name="task_text" placeholder="Descriptive Task"></textarea>
                        <br>
                        <button type="submit" name="save_task" value="save_new" class="border w-48"
                            >Submit for Endorsement Now 
                        </button>
                    </form>
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
                            <th class="px-4 py-3 w-3/4">{{ 'Remarks' }}</th>
                            <th class="px-4 py-3">{{ 'Head' }}</th>
                        </thead>

                        @foreach ( $user->tasks as $current_task)
                        <tr class="border-b-2">

                            <td class="px-4 py-3">
                                {{ $current_task->task_done }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $current_task->created_at->format('m/d/y') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $current_task->status }}
                            </td>
                            
                            <td class="px-4 py-3">
                                {{ $current_task->remarks }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $current_task->user->head->user->name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $current_task->head }}
                            </td>

                        </tr>
                        @endforeach
                       
                    </table>


                </div>

            </div>

        </div>

</x-app-layout>


