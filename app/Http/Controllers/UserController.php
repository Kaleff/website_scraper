<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Show login form
    public function login()
    {
        return view('login');
    }
    // Authenticate user
    public function authenticate(Request $request)
    {
        // Validate and attempt authentification
        $formfields = $request->validate([
            'email' => ['required', 'email'],
            'password' => "required"
        ]);
        if(auth()->attempt($formfields)) {
            $request->session()->regenerate();
            return redirect('/')->with('message', 'You are all set!');
        }
        return back()->withErrors(['email' => 'Invalid data'])->onlyInput();
    }

    // Log out user

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have logged out');
    }
}
