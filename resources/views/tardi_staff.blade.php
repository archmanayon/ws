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
            <h3 >
                {{ 'Academic Year 2022-2023' }}
            </h3>

            <h4>
                {{ 'From the month of March' }}
            </h4>
        </div>

        <div class="lg:grid lg:px-8 m-5 mx-6 sm:px-6">

            {{-- 1st column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                   
                    {{ $user->name??false }} <br>
                    {{ $user->head->department??false }}
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
                                {{ '30' }}
                            </td>
                        </tr>

                        {{-- tardiness --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Reported Tardiness' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ 'Ten (10) times late' }}
                            </td>
                        </tr>

                        {{-- action to take --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Action to be taken' }}
                            </td>

                            <td class="px-4 py-3">
                                {{-- if signed by head, show --}}
                                {{ 'Oral warning by immediate Head' }}
                            </td>
                        </tr>

                        <tr>
                            
                            <td class="px-4 py-3">
                                <a href="">
                                {{ 'click to address' }}
                                </a>
                            </td>
                        </tr>
                       

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


