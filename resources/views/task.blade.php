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

            <div>
                @if($errors->any())
                    <div class="alert alert-danger alert alert-danger text-2xl text-white">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif       
            </div>

            {{-- 1st column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-full">

                <div class="dark:text-gray-100 p-6">
                    {{ 'Task Done' }}<br>
                    {{ $user->name??false }} <br>

                           

                    <form method="post" action="{{ route('store_task') }}">
                        @csrf
                        <textarea class=" rounded-t-lg m-5 w-5/6 mx-auto dark:bg-gray-800 dark:text-gray-100"
                           id="" name="task_text" placeholder="Descriptive Task"></textarea>
                        <br>

                        <button type="submit" name="save_task" value="save_new"
                        class=" flex-none font-extrabold px-2 border dark:bg-gray-200 dark:text-gray-800 py-2 rounded-full w-48"
                            > Submit
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg> --}}


                        </button>
                    </form>
                </div>

            </div>

            {{-- 2nd column --}}
            <div class="m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-full">

                <div class="dark:text-gray-100 p-6 dark:text-gray-100">

                    <table class="rounded-t-lg text-sm w-full">
                        <thead class="border-b border-gray-300 text-left">

                            <th class="px-4 py-3">{{ 'Date' }}</th>

                            <th class="px-4 w-auto">{{ 'Tasks' }}</th>

                            <th class="px-3 py-3  w-auto">
                                {{ 'Remarks' }}
                            </th>

                            <th class="px-4 py-3 w-auto">{{ 'Status' }}</th>

                            {{-- <th class="px-4 py-3">{{ 'Head' }}</th> --}}
                        </thead>

                        @foreach ( $user->tasks->sortBy('status') as $current_task)
                        <tr class="border-b">

                            <td class="px-4">
                                {{ $current_task->created_at->format('m/d/y') }}
                            </td>

                            <td class="px-4 sm:max-w-sm ">
                                {{ $current_task->task_done }}
                            </td>

                            <td class="w-auto ">
                                {{ $current_task->remarks }}
                            </td>

                            <td class="px-4">

                                {!! $current_task->status == 1 ? "<span class='text-green-400'>Endorsed</span>" :
                                    ($current_task->status == 2 ? "<span class='text-red-400'>Disapproved</span>":
                                    "<span class='text-red-400'>Pending</span>")
                                !!}

                            </td>

                            <td class="px-4">
                                {{$current_task->status?$current_task->head->user->name:''}}
                                {{-- {{ $current_task->head->user->name }} --}}
                            </td>

                            {{-- <td class="px-4 py-3">
                                {{ $current_task->head }}
                            </td> --}}

                        </tr>
                        @endforeach

                    </table>


                </div>

            </div>

        </div>

</x-app-layout>


