@php

    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use App\Models\Task;


@endphp

<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-2">        

        <div class="lg:grid lg:px-8 m-5 mx-6 sm:px-6">

            {{-- 1st column --}}
            <div class="m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="py-6 pl-10 text-lg text-gray-900 dark:text-gray-100">

                    <div class="border-b pb-3 w-96">
                        <h2>
                            {{ 'Personnel Tardiness Variance Record' }}
                        </h2>
                        <h3 >
                            {{ 'Academic Year: ' }}{{ $tardis->term->school_year }}
                        </h3>
        
                        <h4>
                            {{ 'From the month of ' }}{{ Carbon::create()->month($tardis->month)->format('F')}}
                        </h4>
                    </div>

                    <div class="pt-3">

                        {{ $tardis->user->name??false }} <br>

                        {{ $tardis->user->head->department??false }}

                    </div>

                </div>

            </div>

            {{-- 2nd column --}}
            <div class="m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="dark:bg-gray-800 rounded-t-lg text-sm w-3/4">

                        {{-- total --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Total Number of Times Late' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $tardis->total??false }}
                            </td>
                        </tr>

                        {{-- tardiness --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Reported Tardiness' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $tardis->tardi_description->tardiness??false }}
                            </td>
                        </tr>

                        {{-- action to take --}}
                        <tr>
                            <td class="px-4 py-3">
                                {{ 'Action to be taken' }}
                            </td>

                            <td class="px-4 py-3">
                                {{-- if signed by head, show --}}
                                {{ $tardis->tardi_description->action??false }}
                            </td>
                        </tr>

                    </table>

                </div>

            </div>

            {{-- 3rd --}}
            <div class="dark:bg-gray-800 m-5 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-3 text-gray-900 dark:text-gray-100">

                    @if (!$tardis->conforme)

                        <form method="POST" action="{{route('post_tardi_group')}}" >
                            @csrf

                            <tr class="px-4 py-3">

                                <textarea class="dark:bg-gray-800 border-gray-700 h-20 rounded text-gray-200 text-sm w-3/4"
                                        id="" name="h_remarks" placeholder="{{ 'dept. head remarks...' }}"></textarea>
                            </tr><br>
                            <tr>
                                <input type="hidden" name="head_email" value="{{ $tardis->head->user_id }}">

                                <button class="border border-gray-400 font-sans rounded-md text-base w-20" type="submit" name="post_address" value="{{ $tardis->id??false }}">Submit</button>

                            </tr>

                        </form>

                    @endif

                </div>

            </div>

            {{-- 4th --}}

                <a href="tardi_group" class="dark:bg-gray-800 dark:hover:text-white dark:text-gray-400 focus:outline focus:outline-2 focus:outline-red-500 focus:rounded-sm font-semibold h-8 hover:text-gray-900 m-5 overflow-hidden shadow-sm sm:rounded-lg text-center text-gray-600 text-xl w-20">
                   {{ 'Back' }}
                </a>



        </div>

</x-app-layout>


