@php
    use Illuminate\Support\Str;
    use \Carbon\Carbon;
@endphp

<x-guest-layout>
    <div class="lg:grid lg:grid-cols-2">

        <div class="">
            <div class="text-center">
                <div class = "bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg w-full">
                    <div class="px-1 text-gray-900 dark:text-gray-100">
                        <div id="" class="text-5xl mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600                              focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        >
                            <div id="m_clock"class="dark:text-gray-100 text-center text-gray-900">
                            </div>
                        </div>
                    </div>
                </div>
            </div>           

        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" id="myElement">

                    <div id="here" class="p-6 text-gray-900 dark:text-gray-100">here</div>

                    <div id="there" class="p-6 text-gray-900 dark:text-gray-100" >there</div>

                </div>
            </div>
        </div>

    </div>
</x-guest-layout>


