<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CustomerRegisterController extends Controller
{
    // Show the registration form
    public function show()
    {
        return view('auth.register'); // your Blade view
    }

    // Handle form submission
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'lname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $customer = Customer::create([
            'name' => $request->name . ' ' . $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Log the customer in
        auth()->guard('customer')->login($customer);

        return redirect('/')->with('success', 'Registration successful!');
    }
}
