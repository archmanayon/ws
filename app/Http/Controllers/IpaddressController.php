<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ipaddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class IpaddressController extends Controller
{
    public function create(): View
    {        

        return view('ip_reg',
            [
                'ip_reg_message' => session('session_ip_enrolled')??false
            ]);



    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {        
        $request->validate([            
            'ipadd' => ['string']
            // 'eff_date' => ['date'],
            // 'end_date' => ['date']
            
        ]);

        $user = Ipaddress::create([
            
            'ip' => $request->ipadd,
            'area' => request('area')?? '',
            'eff_date' => request('eff_date')?? null,
            'end_date' => request('end_date')?? null,
            'remarks' => request('ip_remarks')?? '',
        ]);     

        return redirect()->route('ip_reg')
        ->with('session_ip_enrolled', 'successfully added an IP');
        
    }
}
