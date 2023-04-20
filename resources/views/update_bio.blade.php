@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                       
        </h2>
    </x-slot>

    @php
        $bio_date = Carbon::createFromFormat('mdy', $new_trial[0]->date_bio)
                    ->format('F j, Y')
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:grid lg:grid-cols-2 lg:px-8 ">
            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    

                    <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                        <tr class="text-left border-b border-gray-300">
                            <th class="px-4 py-3">
                                {{  $bio_date }}
                            </th>
                            
                            
                        </tr>     

                        @foreach ($new_trial as $bio_punch)
                            <tr>                                                                
                                <td>
                                    <div class="mt-4 block " >                                        
                                        {{ $bio_punch->hour.$bio_punch->in_out }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                </div>               

            </div>

            <div class="bg-white m-5 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg w-3/4">

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                        <thead class="text-left border-b border-gray-300">
                            <th></th>
                            <th class = "px-4 py-3">
                                {{  $bio_date }}
                            </th> 
                        </thead>  
                            <tr>
                                <td class="mt-4"> <x-input-label for="am_in" :value="__('AM_In')" />   
                                </td>
                                
                                <div class="mt-4" > 
                                    <td>                                       
                                        <x-text-input  id="am_in" class="block mt-1" type="text" placeholder="{{ $new_trial[0]->hour }}l" name="am_in" required autofocus autocomplete="am_in" />
                                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                    </td>
                                </div>
                                
                            </tr>
                            <tr>
                                <td class="mt-4"> <x-input-label for="am_out" :value="__('AM_Out')" />   
                                </td>
                                <td>
                                    <div class="mt-4" >                                        
                                        <x-text-input  id="retain_time" class="block mt-1" type="text" placeholder="{{ $new_trial[1]->hour }}" name="retain_time" required autofocus autocomplete="retain_time" />
                                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="mt-4"> <x-input-label for="pm_in" :value="__('PM_In')" />   
                                </td>
                                <td>
                                    <div class="mt-4" >                                        
                                        <x-text-input  id="retain_time" class="block mt-1" type="text" placeholder="{{ $new_trial[2]->hour }}" name="retain_time" required autofocus autocomplete="retain_time" />
                                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="mt-4"> <x-input-label for="pm_out" :value="__('PM_Out')" />   
                                </td>
                                <td>
                                    <div class="mt-4" >                                        
                                        <x-text-input  id="retain_time" class="block mt-1" type="text" placeholder="{{ $new_trial[3]->hour }}" name="retain_time" required autofocus autocomplete="retain_time" />
                                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
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
                            
                       
                    </table>

                </div>               

            </div>
            
        </div>        

</x-app-layout>


