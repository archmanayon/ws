@php
    use Illuminate\Support\Str;
    // use Illuminate\Support\Collection;
    use Carbon\Carbon;
    use App\Models\Update_bio;
    use App\Models\Punchtype;

@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">

        </h2>
    </x-slot>

    @php
        $bio_date = Carbon::createFromFormat('mdy', $str_date??false)
                    ->format('F j, Y');
        $bio_day = Carbon::parse($bio_date);
    @endphp

    <div class="py-12">

        <form method="POST" action="{{ $str_tc.$str_date }}" >
            @csrf

            
                <div class="grid grid-cols-3">
                    {{-- 1st column --}}
                    <div class="bg-white m-5 dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">

                        <div class="p-6 text-gray-900 dark:text-gray-100">

                            <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                                <tr class="text-left border-b border-gray-300 ">
                                    original
                                    <th></th>
                                    <th class="px-4 py-3">
                                        {{  $bio_date.' | '. $bio_day->format('l') }}
                                    </th>

                                </tr>

                                @php $inde_x = 4; @endphp

                                @foreach ($rawbio as $bio_punch)

                                    <tr>    <td></td>
                                        <td class="px-4 py-3">
                                            <div class="mt-2 block " >
                                                <input class="" type="checkbox" name="new_bio[{{ $inde_x }}]" id=""
                                                    value="{{ $bio_punch->hour }}"  {{ old("new_bio.$inde_x")??false ?  'checked':''  }}
                                                >
                                                <label class="" for="new_bio['{{ $inde_x }}']">
                                                    {{ $bio_punch->hour." | ".$bio_punch->in_out." | ".$bio_punch->in_out." | ".$inde_x." | "}} {{ old("new_bio.$inde_x") }}
                                                </label>

                                                {{-- {{ $orig_bio[$loop->index]->punchtype->punchtype }} --}}
                                                {{ $bio_punch->punchtype->punchtype }}

                                            </div>
                                        </td>

                                        <td class="px-4 py-3">
                                            
                                        </td>
                                    </tr>

                                    @php $inde_x++ @endphp

                                    <tr>

                                        <td>
                                            @if ($loop->last)
                                                {{-- {{ $loop->index }} --}}
                                                @php

                                                    $inde_x = 4;

                                                @endphp
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </table>

                        </div>

                    </div>

                     {{-- 2nd column --}}
                    <div class="bg-white m-5 dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">

                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ $searched_user->name }}

                            <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                                <tr class="text-left border-b border-gray-300">
                                    <th></th>
                                    <th class="px-4 py-3">
                                        {{  "DATE:".$bio_date }}
                                    </th>
                                </tr>

                                {{-- am in --}}
                                <tr>
                                    <td class="text-lg"> <x-input-label for="new_am_in" :value="__('AM In')" /> </td>
                                    <td>

                                        <x-text-input  id="new_am_in" class="block mt-1" type="text"
                                            value="{{ old('new_bio.0')??false }}"
                                            placeholder="{{ $official->am_in??false }}"
                                            name="new_bio[0]" autofocus autocomplete="" />
                                        <x-input-error :messages="$errors->get('new_bio.0')" class="mt-2" />


                                    </td>

                                    <td>
                                        {{ $updated_bio[0]->hour??false }}<br>

                                    </td>


                                </tr>

                                {{-- am out --}}
                                <tr>
                                    <td class="text-lg"> <x-input-label for="new_am_out" :value="__('AM Out')" /> </td>
                                    <td>

                                        <x-text-input  id="new_am_out" class="block mt-1" type="text"
                                            value="{{ old('new_bio.1')??false }}"
                                            placeholder="{{ $official->am_out??false }}"
                                            name="new_bio[1]" autofocus autocomplete="" />
                                        <x-input-error :messages="$errors->get('new_bio.1')" class="mt-2" />

                                    </td>
                                    <td>
                                        {{ $updated_bio[1]->hour??false }}
                                    </td>

                                </tr>

                                {{-- pm in --}}
                                <tr>
                                    <td class="text-lg"> <x-input-label for="new_pm_in" :value="__('PM In')" /> </td>
                                    <td>

                                        <x-text-input  id="new_pm_in" class="block mt-1" type="text"
                                            value="{{ old('new_bio.2')??false }}"
                                            placeholder="{{ $official->pm_in??false }}"
                                            name="new_bio[2]" autofocus autocomplete="" />
                                        <x-input-error :messages="$errors->get('new_bio.2')" class="mt-2" />

                                    </td>
                                    <td>
                                        {{ $updated_bio[2]->hour??false }}

                                    </td>


                                </tr>

                                {{-- pm out --}}
                                <tr>
                                    <td class="text-lg"> <x-input-label for="new_pm_out" :value="__('PM Out')" /> </td>
                                    <td>

                                        <x-text-input  id="new_pm_out" class="block mt-1" type="text"
                                            value="{{ old('new_bio.3')??false }}"
                                            {{-- placeholder="{{ $pref_bio[3]->hour??$official->pm_out??
                                                            $pref_bio[2]->hour??$pref_bio[1]->hour??false }}" --}}
                                            placeholder="{{ $official->pm_out??false }}"
                                            name="new_bio[3]" autofocus autocomplete="" />
                                        <x-input-error :messages="$errors->get('new_bio.3')" class="mt-2" />

                                    </td>
                                    <td>
                                        {{ $updated_bio[3]->hour??false }}

                                    </td>

                                </tr>

                                {{-- SOURCE --}}
                                <tr>
                                    <td></td>
                                    <td class="block dark:bg-gray-700 mt-1 rounded-md shadow-sm">
                                        @php
                                            $options = [
                                                'Bio'   => 1,
                                                'Lv'    => 2,
                                                'Mp'    => 3,
                                                'Do'    => 4,
                                                'DTR'    => 5,
                                                'List'    => 6,
                                                'Others'  => 7
                                            ];
                                        @endphp

                                        <select name="punch_source" class="border-transparent dark:bg-gray-700 dark:text-gray-300">
                                            <option value="{{ null }}">Select Punch Source</option>
                                            @foreach ($options as $label => $value)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>                                    

                                        {{-- @error('punch_source')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror --}}
                                    </td>

                                    @if($errors->any())

                                        <tr class="alert alert-danger">
                                            <td></td>
                                            <td>                                                    
                                                @error('punch_source')
                                                    <span class="text-red-400 text-xs text-danger">{{ $message }}</span>
                                                @enderror
                                                                                    
                                            </td>
                                        </tr>
                                    @endif
                                    

                                </tr>

                                <tr>
                                    <td></td>
                                    <td>
                                        <x-text-input  id="reason_bio" class="block mt-1" type="text"
                                            placeholder="Reason"
                                            name="reason_bio" autofocus/>
                                        <x-input-error :messages="$errors->get('reason_bio')" class="mt-2 text-xs" />
                                    </td>
                                </tr>

                                <tr>
                                    <td></td>
                                   
                                    <td>
                                        <button type="submit" name="save_new" value="save_new">Update Bio</button>
                                    </td>
                                </tr>

                            </table>

                        </div>

                    </div>

                    {{-- 3rd column --}}
                    <div class="bg-white m-5 dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">


                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ $searched_user->name }}
                            {{-- display errors here --}}
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li class="text-red-400 text-xs">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                                <tr class="text-left border-b border-gray-300 ">

                                    <th class="px-4 py-3">
                                        {{  $bio_date.' | '. $bio_day->format('l') }}
                                    </th>

                                </tr>

                                @if ($updated_bio??false)

                                    @foreach ($updated_bio as $updated_bio)
                                        <tr>
                                            <td> {{ $updated_bio->biotext  }}</td>
                                        </tr>

                                    @endforeach

                                @endif

                            </table>

                        </div>

                    </div>
                </div>
            


        </form>



</x-app-layout>


