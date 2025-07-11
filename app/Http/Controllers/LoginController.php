<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // Tampilkan error hanya jika redirect paksa karena belum login
        if ($request->session()->previousUrl() && !Auth::check()) {
            if (!$request->session()->has('success')) {
                session()->flash('error', 'Silakan login terlebih dahulu.');
            }
        }

        return view('auth.login');
    }




    public function login(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('nik', $request->nik)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // 💬 Tambahkan notifikasi sukses
            session()->flash('success', 'Login berhasil! Selamat datang, ' . $user->nama);

            // Redirect berdasarkan role
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['login' => 'NIK atau Password salah']);
    }


    public function logout()
    {
        session()->forget('error'); // Hapus error sebelumnya
        Auth::logout();
        session()->flash('success', 'Berhasil logout!');
        return redirect()->route('login');
    }


}
