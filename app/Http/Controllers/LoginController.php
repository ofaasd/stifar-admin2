<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PegawaiBiodatum;
use App\Models\ModelHasRole;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    //
    public function landing(){
        return view('landing');
    }
    public function login(){
        $url = Url::to('/');

        if (Auth::check()) {
            $role = Auth::User()->roles->pluck('name');
            if($role[0] == "mhs"){
                return redirect('mhs/dashboard');
            }elseif($role[0] == "pegawai"){
                return redirect('dsn/dashboard');
            }else{
                return redirect('dashboard');
            }

        }else{
            if($url == 'mhs.stifar.id'){
                return view('login_mhs');
            }elseif($url == 'dsn.stifar.id'){
                return view('login_dsn');
            }else{
                return view('login');
            }
        }
    }
    public function login_mhs(){
        if (Auth::check()) {
            $role = Auth::User()->roles->pluck('name');
            if($role[0] == "mhs"){
                return redirect('mhs/dashboard');
            }elseif($role[0] == "pegawai"){
                return redirect('dsn/dashboard');
            }else{
                return redirect('dashboard');
            }

        }else{
            return view('login_mhs');
        }
    }
    public function login_dsn(){
        if (Auth::check()) {
            $role = Auth::User()->roles->pluck('name');
            if($role[0] == "mhs"){
                return redirect('mhs/dashboard');
            }elseif($role[0] == "pegawai"){
                return redirect('dsn/dashboard');
            }else{
                return redirect('dashboard');
            }

        }else{
            return view('login_dsn');
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
            $role = Auth::User()->roles->pluck('name');
            if($role[0] == "mhs"){
                $mhs = Mahasiswa::where('user_id',Auth::user()->id)->first();
                if($mhs->update_password == 0){
                    return redirect('mhs/profile');
                }else{
                    return redirect('mhs/dashboard');
                }
            }elseif($role[0] == "pegawai"){
                return redirect('dsn/dashboard');
            }else{
                return redirect('dashboard');
            }
            // if($role[0] == "pegawai"){
            //     $dosen =
            // }


            // return redirect('dashboard');
        }else{
            Session::flash('error', 'Email atau Password Salah');
            return redirect('/');
        }
    }
    public function actionRegister(Request $request){
        $npp = $request->nip;
        $pegawai = PegawaiBiodatum::where('npp',$npp);
        if($pegawai->count() > 0){
            $new_pegawai = $pegawai->first();
            $cek_email = User::where('email',$request->email);
            if($cek_email->count() > 0){
                Session::flash('message_error', 'Email Sudah pernah di daftarkan');
            }else{
                $cek_user = User::where('id',$new_pegawai->user_id);
                if($cek_user->count() == 0){
                    $user = User::create([
                        'name' => $new_pegawai->nama_lengkap,
                        'email' => $request->email,
                        'password' => Hash::make($request->password)
                    ]);
                    $id = $user->id;
                    $update_pegawai = PegawaiBiodatum::find($new_pegawai->id);
                    $update_pegawai->user_id = $id;
                    $update_pegawai->save();

                    $role = ModelHasRole::create(
                        [
                            'role_id' => 3,
                            'model_type' => 'App\Models\User',
                            'model_id' => $id,
                        ]
                    );

                    Session::flash('message', 'Register Berhasil. Akun Anda sudah Aktif silahkan Login menggunakan email dan password.');
                }else{
                    Session::flash('message_error', 'User Sudah Pernah di daftarkan. Hubungi Admin untuk merubah password');
                }
            }
        }else{
            Session::flash('message_error', 'NIP pegawai tidak ditemukan');
        }

        return redirect('register');
    }
    public function actionRegisterMhs(Request $request){
        $nim = $request->nim;
        $mhs = Mahasiswa::where('nim',$nim);
        if($mhs->count() > 0){
            $new_mhs = $mhs->first();
            $cek_email = User::where('email',$request->email);
            if($cek_email->count() > 0){
                Session::flash('message_error', 'Email Sudah pernah di daftarkan');
            }else{
                $cek_user = User::where('id',$new_pegawai->user_id);
                if($cek_user->count() == 0){
                    $user = User::create([
                        'name' => $new_pegawai->nama_lengkap,
                        'email' => $request->email,
                        'password' => Hash::make($request->password)
                    ]);
                    $id = $user->id;
                    $update_pegawai = PegawaiBiodatum::find($new_pegawai->id);
                    $update_pegawai->user_id = $id;
                    $update_pegawai->save();

                    $role = ModelHasRole::create(
                        [
                            'role_id' => 3,
                            'model_type' => 'App\Models\User',
                            'model_id' => $id,
                        ]
                    );

                    Session::flash('message', 'Register Berhasil. Akun Anda sudah Aktif silahkan Login menggunakan email dan password.');
                }else{
                    Session::flash('message_error', 'User Sudah Pernah di daftarkan. Hubungi Admin untuk merubah password');
                }
            }
        }else{
            Session::flash('message_error', 'NIP pegawai tidak ditemukan');
        }

        return redirect('register');
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
