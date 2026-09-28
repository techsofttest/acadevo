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
    // Handle standard email/password login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('customer')->attempt($credentials)) {
            $request->session()->regenerate();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Logged in successfully',
                    'redirect' => url('/')
                ]);
            }

            return redirect()->intended('/')->with('success', 'Logged in successfully');
        }

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

    // Step 1: Request Mobile OTP
    public function sendMobileOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'regex:/^[0-9]{10,15}$/'],
        ], [
            'phone.required' => 'Please enter your mobile number.',
            'phone.regex'    => 'Please enter a valid mobile number (10 digits).',
        ]);

        $phone = trim($request->phone);

        // Store phone in session for OTP verification
        session([
            'customer_login_phone' => $phone,
            'customer_login_otp'   => '0000', // Static dummy OTP
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully! (Use dummy OTP: 0000)',
            'phone'   => $phone,
        ]);
    }

    // Step 2: Verify Mobile OTP & Log In
    public function verifyMobileOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'otp'   => 'required',
        ], [
            'phone.required' => 'Mobile number is required.',
            'otp.required'   => 'Please enter the OTP.',
        ]);

        $phone = trim($request->phone);
        $otp = trim($request->otp);

        $sessionPhone = session('customer_login_phone');

        // Check if OTP matches dummy '0000'
        if ($otp !== '0000' && $otp !== session('customer_login_otp')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP! Please enter dummy OTP 0000.'
            ], 422);
        }

        // Find existing customer by phone or create new one
        $customer = Customer::where('phone', $phone)->first();

        if (!$customer) {
            $customer = Customer::create([
                'name'     => 'User ' . substr($phone, -4),
                'phone'    => $phone,
                'email'    => null,
                'password' => null,
            ]);
        }

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();
        session()->forget(['customer_login_phone', 'customer_login_otp']);

        return response()->json([
            'success'  => true,
            'message'  => 'Logged in successfully!',
            'redirect' => url('/'),
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

