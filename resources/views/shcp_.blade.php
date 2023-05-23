@php
    use Illuminate\Support\Str;
    use \Carbon\Carbon; 
@endphp
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ 'Dashboard' }}
    </h2>
</x-slot>

<x-guest-layout>
    <div class="py-6">
        <div class="mx-6 max-w-xl mx-auto sm:px-6 lg:px-8 text-center">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-6 ">

                <div class="px-1 text-gray-900 dark:text-gray-100">
                        <td>

                            <div class="text-6xl mt-4 order-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600
                                focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                {{$date_->format('h:i A') }}
                            </div>
                        </td>
                        <td>
                            {{$date_->format('F j, Y')  }}
                            
                        </td>
                       
                </div>
            </div>
            
        </div>  
       
    </div>

    
    <!-- Session Status -->
    <x-auth-session-status class= "m-auto-3" :status="session('status')" />

    <form method="POST" action="{{ route('punches_') }}">
        @csrf

        <!-- USC ID Number -->
        <div>
            <x-input-label for="student_id" :value="__('USC ID Number')" />
            <x-text-input id="student_id" class="block mt-1 w-full" 
                            type="text" name="student_id" 
                            :value="old('student_id')" 
                            required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="punch_pw" :value="__('Password')" />

            <x-text-input id="punch_pw" class="block mt-1 w-full"
                            type="password"
                            name="punch_pw"
                            required autocomplete="current-punch_pw" />

            <x-input-error :messages="$errors->get('punch_pw')" class="mt-2" />
        </div>
      

        <div class="">
            
            <x-primary-button class="m-auto-3 py-1 px-6 mt-4 mx-auto w-auto">
                {{-- {{ __('Punch with your USC ID') }} --}}
                {{ __( $in_out ? ($in_out == 'I'? 'Punch In with your USC ID' : 
                    'Punch Out with your USC ID') :
                    'Punch In with your USC ID') }}
            </x-primary-button>

        </div>
    </form>
      
    <!-- punches -->
    <div class="py-6">
                                
        <table class=" text-center max-w-xl mx-auto sm:px-6 lg:px-8 p-6 text-gray-900 dark:text-gray-100" >
                @if ($punches_today)
                    
               
                    @foreach ( $punches_today as $shcp)
                            <tr>
                            
                            <td> 
                                {{ 
                                    Carbon::createFromFormat('Hi', $shcp->hour??false)
                                    ->format('h:i a')
                                }}
                            </td>
                        
                            <td class="px-3">
                                {{ '|' }}
                            </td>
                            
                            <td class=""> 
                                {{ 
                                    $shcp->in_out == 'I'? 'In' : 'Out'
                                }}
                            </td>
                            
                        </tr>
                    @endforeach
                     
             @endif
           

    </div>

</x-guest-layout>
