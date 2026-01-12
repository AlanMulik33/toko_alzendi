<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    public function showLoginForm() {
        return view('auth.customer'); // buat view ini
    }

    public function showRegisterForm() {
        return view('auth.customer_register');
    }

    public function login(Request $request) {
        $credentials = $request->only('email','password');

        if (Auth::guard('customer')->attempt($credentials, $request->filled('remember'))) {
            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors(['email' => 'Email or password incorrect']);
    }

    public function logout(Request $request) {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        return redirect()->route('customer.login');
    }

    // optional: register pelanggan lewat form (hash password)
    public function register(Request $request) {
        $data = $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:customers,email',
            'password'=>'required|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $customer = Customer::create($data);
        Auth::guard('customer')->login($customer);
        return redirect()->route('customer.dashboard');
    }
}
