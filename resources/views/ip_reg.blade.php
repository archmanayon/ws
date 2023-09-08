
<x-guest-layout>

    <div class="mt-4 text-green-600">
        {{ $ip_reg_message??false }}
    </div>

    <form method="POST" action="{{ route('ip_reg') }}">
        @csrf

        <!-- IP Add -->
        <div class="mt-4">
            <x-input-label for="ipadd" :value="__('IP Address')" />
            <x-text-input id="ipadd" class="block mt-1 w-full" type="text" name="ipadd" :value="old('ipadd')" required autofocus autocomplete="ipadd" />
            <x-input-error :messages="$errors->get('ipadd')" class="mt-2" />
        </div>

         <!-- Area -->
         <div class="mt-4">
            <x-input-label for="area" :value="__('AREA')" />
            <x-text-input id="area" class="block mt-1 w-full" type="text" name="area" :value="old('area')" required autocomplete="area" />
            <x-input-error :messages="$errors->get('area')" class="mt-2" />
        </div>      

        <!-- Effective Date -->
        <div class="mt-4">
            
            <x-input-label for="eff_date" :value="__('Effective Date')" />
            <x-text-input  id="eff_date" class="block mt-1 w-full" type="date" name="eff_date" :value="old('eff_date')" autofocus autocomplete="eff_date" />
            <x-input-error :messages="$errors->get('eff_date')" class="mt-2" />
        </div>   
        
         <!-- End Date -->
         <div class="mt-4">
            
            <x-input-label for="end_date" :value="__('End Date')" />
            <x-text-input  id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')"/>
            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
        </div>

        <!-- Remarks -->
        <div class="mt-4">
            <x-input-label for="ip_remarks" :value="__('Remarks')" />
            <x-text-input id="ip_remarks" class="block mt-1 w-full" type="text" name="ip_remarks" :value="old('ip_remarks')" autofocus autocomplete="ip_remarks" />
            <x-input-error :messages="$errors->get('ip_remarks')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">            

            <x-primary-button class="ml-4">
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
