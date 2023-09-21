@php

    use Illuminate\Support\Str;
    use Carbon\Carbon;


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

                <div class="dark:text-gray-100 p-6">

                    <table class="rounded-t-lg text-sm w-full">
                        <thead class="border-gray-300 text-left">

                            <th class="px-4 w-auto">{{ 'Dept' }}</th>

                            <th class="px-4 py-3">{{ 'Head' }}</th>

                            <th class="px-4 py-3">{{ 'Members' }}</th>
                          
                        </thead>

                        @foreach ( $department as $department)

                        <tr class="border-t">

                                {{--department --}}
                                <td class="px-4">
                                    {{ $department->id." | ".$department->department}}
                                </td>

                                 {{--Head --}}
                                 <td class="px-4">
                                    {{ $department->user->id." | ".$department->user->name}}
                                </td>          
                        
                                {{--members --}}
                                @foreach ($department->users->where('active',1)->sortBy('name') as $members)

                                    @if ($loop->index == 0)

                                        <td class="px-4">
                                            {{ $members->id." | ".$members->name}}
                                        </td></tr>
                                        
                                        
                                    @else
                                    
                                    <tr>
                                       <td></td> <td></td>
                                    
                                    <td class="px-4">
                                        {{ $members->id." | ".$members->name}}
                                    </td></tr>

                                    @endif  
                                @endforeach 
                            

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


