<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // LOGIN PAGE
    public function login()
    {
        return view('auth.login');
    }

    // REGISTER PAGE
    public function register()
    {
        return view('auth.register');
    }

    // REGISTER SAVE
    public function registerStore(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role_id'=>1
        ]);

        return redirect('/login')->with('success','Account Created');
    }

    // LOGIN CHECK
    public function loginCheck(Request $request)
    {
        if(Auth::attempt($request->only('email','password')))
        {
            return redirect('/dashboard');
        }

        return back()->with('error','Invalid Credentials');
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}