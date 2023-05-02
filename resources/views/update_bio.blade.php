@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use App\Models\Update_bio;

@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

        </h2>
    </x-slot>

    @php
        $bio_date = Carbon::createFromFormat('mdy', $str_date??false)
                    ->format('F j, Y')
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:grid lg:grid-cols-2 lg:px-8 ">

            {{-- 1st column --}}
            {{-- <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">


                    <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                        <tr class="text-left border-b border-gray-300 ">
                            original
                            <th></th>
                            <th class="px-4 py-3">
                                {{  $bio_date }}
                            </th>

                        </tr>

                        @foreach ($old_bio as $bio_punch)
                            <tr>    <td></td>
                                <td class="px-4 py-3">
                                    <div class="mt-2 block " >
                                        {{ $bio_punch->hour.$bio_punch->in_out }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>

            </div> --}}

            {{-- 2nd column --}}
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">


                    <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                        <tr class="text-left border-b border-gray-300">
                            <th></th>
                            <th class="px-4 py-3">
                                {{  "DATE:".$bio_date }}
                            </th>
                        </tr>

                        <form method="POST" action="{{ $str_tc.$str_date }}" >
                            @csrf
                            <tr>

                                {{-- @if ($am ?? false)

                                    @foreach ( $am as $key => $value)

                                        {{ $key.'|'.$value }} <br>

                                    @endforeach

                                @endif --}}

                                {{-- {{ $pref_bio[0]->hour?? false }} --}}

                            </tr>

                            {{-- am in --}}
                            <tr>
                                <td class="text-lg"> <x-input-label for="new_am_in" :value="__('AM In')" /> </td>
                                    <td>

                                        <x-text-input  id="new_am_in" class="block mt-1" type="text"
                                            value="{{ old('new_bio.0')?:($pref_bio[0]->hour??false) }}"
                                            name="new_bio[]" required autofocus autocomplete="{{ ($pref_bio[0]->hour??false )}}" />
                                        <x-input-error :messages="$errors->get('new_bio.0')" class="mt-2" />


                                    </td>

                                <td>
                                    {{-- {{ $updated_bio[0]->hour??false }}<br> --}}

                                </td>


                            </tr>

                            {{-- am out --}}
                            {{-- <tr>
                                <td class="text-lg"> <x-input-label for="new_am_out" :value="__('AM Out')" /> </td>
                                <td>

                                    <x-text-input  id="new_am_out" class="block mt-1" type="text"
                                        value="{{ old('new_bio.1')?:$pref_bio[1]->hour??false }}"
                                        name="new_bio[]" required autofocus autocomplete="{{ $pref_bio[0]->hour??false }}" />
                                    <x-input-error :messages="$errors->get('new_bio.1')" class="mt-2" />

                                </td>
                                <td>
                                    {{ $updated_bio[1]->hour??false }}
                                </td>

                            </tr> --}}

                            {{-- pm in --}}
                            {{-- <tr>
                                <td class="text-lg"> <x-input-label for="new_pm_in" :value="__('PM In')" /> </td>
                                <td>

                                    <x-text-input  id="new_pm_in" class="block mt-1" type="text"
                                        value="{{ old('new_bio.2')?:$pref_bio[2]->hour??$pref_bio[0]->hour }}"
                                        name="new_bio[]" autofocus autocomplete="{{ $pref_bio[2]->hour??false }}" />
                                    <x-input-error :messages="$errors->get('new_bio.2')" class="mt-2" />

                                </td>
                                <td>
                                    {{ $updated_bio[2]->hour??false }}

                                </td>


                            </tr> --}}

                            {{-- pm out --}}
                            {{-- <tr>
                                <td class="text-lg"> <x-input-label for="new_pm_out" :value="__('PM Out')" /> </td>
                                <td>

                                    <x-text-input  id="new_pm_out" class="block mt-1" type="text"
                                        value="{{ old('new_bio.3')?:$pref_bio[3]->hour??($pref_bio[2]->hour??false?'':'') }}"
                                        placeholder="{{ $pref_bio[3]->hour??
                                                        $pref_bio[2]->hour??$pref_bio[1]->hour??
                                                        false }}"
                                        name="new_bio[]" autofocus autocomplete="{{ $pref_bio[3]->hour??false }}" />
                                    <x-input-error :messages="$errors->get('new_bio.3')" class="mt-2" />

                                </td>
                                <td>
                                    {{ $updated_bio[3]->hour??false }}

                                </td>

                            </tr> --}}

                            <tr>
                                <td></td>
                                <td>
                                    <x-text-input  id="reason_bio" class="block mt-1" type="text"
                                        placeholder="Reason"
                                        name="reason_bio" required autofocus/>
                                    <x-input-error :messages="$errors->get('reason_bio')" class="mt-2" />
                                </td>
                            </tr>

                            <tr>
                                <td></td>
                                <td>
                                    <button type="submit" name="save_new" value="save_new">Update Bio</button>
                                </td>
                            </tr>

                        </form>
                    </table>

                </div>

            </div>

            {{-- 3rd column https://www.youtube.com/watch?v=S1yXIAjCbQw--}}

            {{-- <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                        <thead class="text-left border-b border-gray-300">
                            <th></th>
                            <th class = "px-4 py-3">
                                {{  $bio_date }}
                            </th>
                        </thead>

                            <form method="POST" action="" >

                                @csrf
                                <tr>
                                    <td class="mt-4"> <x-input-label for="am_in" :value="__('AM_In')" />
                                    </td>

                                    <div class="mt-4" >
                                        <td>
                                            <x-text-input  id="new_am_in" class="block mt-1" type="text" placeholder="{{ $old_bio[0]->hour??false }}" name="new_punch[]" required autofocus autocomplete="new_am_in" />
                                            <x-input-error :messages="$errors->get('new_am_in')" class="mt-2" />
                                        </td>
                                    </div>

                                </tr>
                                <tr>
                                    <td class="mt-4"> <x-input-label for="am_out" :value="__('AM_Out')" />
                                    </td>
                                    <td>
                                        <div class="mt-4" >
                                            <x-text-input  id="new_am_out" class="block mt-1" type="text" placeholder="{{ $old_bio[1]->hour??false }}" name="new_punch[]" required autofocus autocomplete="new_am_out" />
                                            <x-input-error :messages="$errors->get('new_am_out')" class="mt-2" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="mt-4"> <x-input-label for="pm_in" :value="__('PM_In')" />
                                    </td>
                                    <td>
                                        <div class="mt-4" >
                                            <x-text-input  id="new_pm_in" class="block mt-1" type="text" placeholder="{{ $old_bio[2]->hour??false }}" name="new_punch[]" required autofocus autocomplete="new_pm_in" />
                                            <x-input-error :messages="$errors->get('new_pm_in')" class="mt-2" />
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="mt-4"> <x-input-label for="pm_out" :value="__('PM_Out')" />
                                    </td>
                                    <td>
                                        <div class="mt-4" >
                                            <x-text-input  id="new_pm_out" class="block mt-1" type="text" placeholder="{{ $old_bio[3]->hour??false }}" name="new_punch[]" required autofocus autocomplete="new_pm_out" />
                                            <x-input-error :messages="$errors->get('new_pm_out')" class="mt-2" />
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td> <x-input-label for="pm_out" :value="__('Reason for Change')" /></td>
                                    <td >
                                        <div class="text-lg " >

                                            <x-text-input  id="change_reason" class="block mt-1" type="text" placeholder="{{ $old_bio[3]->hour??false }}" name="change_reason" required autofocus autocomplete="change_reason" />
                                            <x-input-error :messages="$errors->get('new_pm_out')" class="mt-2" />
                                        </div>
                                    </td>
                                </tr>
                                <tr> <td></td>
                                    <td>
                                        <div class="mt-4 text-lg" >
                                            <button> {{'update >'}} </button>
                                        </div>
                                    </td>
                                </tr>
                            </form>


                    </table>

                </div>

            </div> --}}

        </div>

</x-app-layout>


