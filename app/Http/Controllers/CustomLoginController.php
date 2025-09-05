<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CustomLoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }  
    
    /**
     * ログイン
     * 
     */
    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }
  
        return redirect("login")->withSuccess('Login details are not valid');
    }
    
    public function signOut() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}
