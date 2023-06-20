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
                    {{ $user->head->user->name??false }}
                </div>

            </div>

            {{-- 2nd column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-full">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="bg-gray-800 rounded-t-lg text-sm w-full">
                        <thead class="border-b border-gray-300 text-left">

                            <th class="px-4 py-3">{{ 'Date' }}</th>

                            <th class="px-4 w-auto">{{ 'Tasks' }}</th>                            
                            
                            <th class="px-3 py-3  w-auto"> 
                                {{ 'Remarks' }}
                            </th>

                            <th class="px-4 py-3 w-auto">{{ 'Status' }}</th>

                            {{-- <th class="px-4 py-3">{{ 'Head' }}</th> --}}
                        </thead>

                        @foreach ( $user->heads[0]->tasks->sortBy('status') as $current_task)
                        <tr class="border-b">

                            <form method="post" action="{{ route('endorse_task') }}">
                            @csrf

                                {{-- date --}}
                                <td class="px-4">
                                    {{ $current_task->created_at->format('m/d/y') }}
                                </td>

                                {{-- task --}}
                                <td class="px-4 sm:max-w-sm ">
                                    {{ $current_task->task_done }}
                                </td>                                

                                {{-- remarks --}}
                                <td class="w-auto">   

                                    @if ($current_task->remarks)

                                        {{ $current_task->remarks }}

                                    @else

                                        <textarea class="bg-gray-800 border-gray-700 h-9 mt-2 rounded text-gray-200 text-sm"
                                        id="" name="head_remarks" placeholder="type...">

                                        {{ $current_task->remarks?? false }}
                                        </textarea>

                                    @endif               
                                    
                                </td>

                                {{-- status --}}
                                <td class="px-4 w-auto">

                                   
                                    @if ($current_task->status)

                                        {{ $current_task->status == 1 ? "Endorsed" : 
                                            ($current_task->status == 2 ? "Disapproved" : "Pending") 
                                        }}

                                        @else
                                                                                                
                                            <select name="stat_option" id="task_stat" class="bg-gray-800 border-transparent mt-2 px-0 py-1 rounded text-1xl text-gray-200 w-auto">
                                                <option value="0">Pending</option>
                                                <option value="1">Endorse</option>
                                                <option value="2">Disapprove</option>
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

                                        <button type="submit" name="task_id" value="{{ $current_task->id }}" class="">
                                            Submit
                                        </button>

                                    @endif

                                </td>

                            </form>
                        </tr>
                        @endforeach

                    </table>


                </div>

            </div>

        </div>

</x-app-layout>


