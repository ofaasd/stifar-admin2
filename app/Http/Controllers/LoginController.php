<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    //
    public function login(){
        if (Auth::check()) {
            return redirect('home');
        }else{
            return view('login');
        }
    }
    public function register(){
        return view('register');
    }
    public function actionLogin(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::Attempt($credentials)) {
            return redirect('dashboard');
        }else{
            Session::flash('error', 'Email atau Password Salah');
            return redirect('/');
        }
    }
    public function actionRegister(Request $request){
        $name = $request->name1 . $request->name2;
        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        Session::flash('message', 'Register Berhasil. Akun Anda sudah Aktif silahkan Login menggunakan email dan password.');
        return redirect('register');
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
