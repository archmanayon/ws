@php
    use Illuminate\Support\Str;
    use \Carbon\Carbon;


@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ 'Dashboard' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">                

                <div class="p-6 text-gray-900 dark:text-gray-100 border">

                    <p class="text-lg text-center font-bold m-5">{{ '' }}</p>

                        <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-800 text-gray-200">
                            <tr class="text-left border-b border-gray-300">
                                <th class="px-4 py-3">Department</th>
                                <th class="px-4 py-3">employees</th> 
                                <th class="px-4 py-3">COUNT</th>                                
                            </tr>


                                @foreach ( $mapped_Dept->where('active', '1')->sortBy('department') as $department)

                                    @if ( $department)

                                        <tr class="bg-gray-700 border-b border-gray-600"> 
                                            
                                            <td class="px-4 py-3">
                                                {{ $department->department}}                                             
                                            </td>                                            

                                            <td class="px-4 py-3 text-xs">
                                                @foreach ($department->users->where('role_id', '2') as $user)
                                                
                                                    {{ $user->name."|"}} 
                                                    {{-- <td> {{ $count }}    </td> --}}   
                                                    @if ( $loop->last)
                                                    
                                                        <td> {{ $loop->count }}
                                                        
                                                    @endif                                                 
                                                                                                       
                                                @endforeach
                                                

                                            </td>
                                            
                                        
                                        </tr>
                                    @endif

                                @endforeach

                            

                        </table>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
