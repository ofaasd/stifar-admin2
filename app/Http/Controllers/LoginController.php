<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\TahunAjaran;
use App\Models\LoginAttempt;
use App\Models\ModelHasRole;
use Illuminate\Http\Request;
use App\Models\PegawaiBiodatum;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PegawaiBerkasPendukung;
use Illuminate\Support\Facades\Session;
use App\Models\BerkasPendukungMahasiswa;

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
            if($url == 'https://mhs.stifar.id'){
                return view('login_mhs');
            }elseif($url == 'https://dsn.stifar.id'){
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
            LoginAttempt::create([
                'ip_address' => $request->ip(),
                'time' => date('Y-m-d H:i:s'),
                'user_id' => Auth::user()->id
            ]);
            $ta = TahunAjaran::where("status", "Aktif")->first();

            if($role[0] == "mhs"){
                $mhs = Mahasiswa::where('user_id', Auth::user()->id)->first();

                if (!$mhs) {
                    Auth::logout();
                    Session::flash('error', 'Data Mahasiswa tidak ditemukan, silahkan hubungi admin');
                    return redirect()->route('login');
                }

                if ($mhs->is_yudisium == 1 && empty($mhs->no_pisn)) {
                    Session::flash('show_modal_isi_no_pisn', true);
                }
        
                $session = [
                    'isYudisium' => $mhs->is_yudisium ?? 0,
                ];
                $berkas = BerkasPendukungMahasiswa::where("nim", $mhs->nim)->latest()->first();
                if($mhs->update_password == 0){
                    Session::put($session);
                    return redirect('mhs/profile');
                }else{
                    Session::put($session);
                    $redirect = redirect('mhs/dashboard');
                    if ($ta->id != optional($berkas)->id_ta) {
                        $redirect->with(
                            [
                                'herregistrasi'=> true,
                                'role'=> $role[0],
                            ]
                        );
                    }

                    return $redirect;
                }
            }elseif($role[0] == "pegawai-dosen"){
                $pegawai = PegawaiBiodatum::where('user_id',Auth::user()->id)->first();
                $berkas = PegawaiBerkasPendukung::where("id_pegawai", $pegawai->id)->latest()->first();

                $redirect = redirect('dsn/dashboard');
                if ($ta->id != optional($berkas)->id_ta) {
                    $redirect->with(
                        [
                            'herregistrasi'=> true,
                            'role'=> $role[0],
                        ]
                    );
                }

                return $redirect;
            }elseif($role[0] == "pegawai"){
                $pegawai = PegawaiBiodatum::where('user_id',Auth::user()->id)->first();
                $berkas = PegawaiBerkasPendukung::where("id_pegawai", $pegawai->id)->latest()->first();

                $redirect = redirect('employee/dashboard');
                if ($ta->id != optional($berkas)->id_ta) {
                    $redirect->with(
                        [
                            'herregistrasi'=> true,
                            'role'=> $role[0],
                        ]
                    );
                }

                return $redirect;
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
