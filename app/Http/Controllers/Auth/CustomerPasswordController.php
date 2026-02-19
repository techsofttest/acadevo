<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CustomerPasswordController extends Controller
{


    public function view()
    {
    
    return view('user.change-password');

    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $customer = Auth::guard('customer')->user();

        // Check old password
        if (!Hash::check($request->current_password, $customer->password)) {
            return back()->withErrors(['current_password' => 'Old password does not match']);
        }

        // Update password
        $customer->password = Hash::make($request->new_password);
        $customer->save();

        return back()->with('success', 'Password updated successfully!');
    }
}

