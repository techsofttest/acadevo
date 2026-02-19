<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class CustomerLoginController extends Controller
{
    // Handle standard login
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::guard('customer')->attempt($credentials)) {

        $request->session()->regenerate();

        // If AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully',
                'redirect' => url('/') // or dashboard
            ]);
        }

        return redirect()->intended('/')->with('success', 'Logged in successfully');
    }

    // Failed login
    if ($request->ajax()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    return back()->withErrors([
        'email' => 'Invalid credentials',
    ]);
        }       

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // Redirect to social provider
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Handle social callback
    public function handleProviderCallback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();

        $customer = Customer::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(),
            ]
        );

        Auth::guard('customer')->login($customer);

        return redirect('/')->with('success', "Logged in with {$provider}");
    }
}

