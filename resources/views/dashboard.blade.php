<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">


        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}<br>
                    {{ auth()->user()->student_id }}
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                        @admin
                                <a href="all_absences" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        View All WS Absences
                                </a><br>

                                <a href="print" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        Extract Abs
                                </a><br>

                                <a href="adea" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        View USC EMPLOYEE Absences
                                </a><br>

                                <a href="js" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        Java Script Pratice
                                </a><br>

                                <a href="raw_bio_text" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        View Raw Biometric
                                </a><br>

                                 <a href="text_files" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        Biometric report (text files)
                                </a><br>

                                <a href="task" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        Submit Daily Task Report
                                </a><br>

                                <a href="dtr" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        EMPLOYEES dtr
                                </a><br>

                                <a href="shcp" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        Punch to Biometrics
                                </a><br>

                                {{-- <a href="setup" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        S-E-T U-P  -  E-V-E-R-Y-T-H-I-N-G
                                </a><br> --}}

                        @endadmin

                        @ws

                                <a href="report/{{ auth()->user()->username }}" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        View My Absences
                                </a><br>
                        @endws

                        @staff

                                <a href="my_dtr" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        View My DTR
                                </a><br>



                                 <a href="tardi" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        View Personal Tardiness Variance
                                </a><br>

                        @endstaff

                        @head

                                <a href="dept_head" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        Endorse Tasks
                                </a><br>

                                <a href="tardi_group" class="font-semibold text-gray-600
                                        hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                                        focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                                        View Staff Tardiness Report
                                </a><br>

                        @endhead


                    {{-- @admin

                    <a href="hash_pw" class="font-semibold text-gray-600
                            hover:text-gray-900 dark:text-gray-400 dark:hover:text-white
                            focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            Hash</a>     <br>
                    @endadmin --}}



                </div>

                @admin

                <table>
                        <form method="POST" action="dashboard">
                                @csrf

                                <td>
                                <div class="mt-4" >
                                        <x-input-label for="payroll_start" :value="__('payroll_start')" />
                                        <x-text-input  id="payroll_start" class="block mt-1" type="date" name="payroll_start" :value="request('payroll_start')" required autofocus autocomplete="payroll_start" />
                                        <x-input-error :messages="$errors->get('payroll_start')" class="mt-2" />
                                </div>
                                </td>
                                <td>
                                <div class="mt-4">
                                        <x-input-label for="payroll_end" :value="__('payroll_end')" />
                                        <x-text-input id="payroll_end" class="block mt-1" type="date" name="payroll_end" :value="request('payroll_end')" required autofocus autocomplete="payroll_end" />
                                        <x-input-error :messages="$errors->get('payroll_end')" class="mt-2" />
                                </div>
                                </td>

                                <td>

                                <div class="mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <button type="submit" name="submit_indi" value="">
                                        Submit
                                        </button>
                                </div>
                                </td>

                        </form>
                </table>
                @endadmin


            </div>
        </div>
    </div>
</x-app-layout>
