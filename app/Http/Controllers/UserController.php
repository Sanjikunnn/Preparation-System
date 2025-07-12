<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // tambahkan kalau belum

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $lastNik = User::orderBy('nik', 'desc')->value('nik');
        $nextNik = str_pad((int)$lastNik + 1, strlen($lastNik), '0', STR_PAD_LEFT); // contoh: '00001' → '00002'

        return view('users.create', compact('nextNik'));
    }


    public function store(Request $request) {
        $request->validate([
            'nik' => 'required|unique:users,nik',
            'nama' => 'required',
            'password' => 'required|min:4',
            'role' => 'required|in:Admin,Kasir,Customer',
        ]);

        User::create([
            'nik' => $request->nik,
            'nama' => $request->nama,
            'role' => $request->role,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($nik) {
        $this->authorizeSupervisor(); // batasi hanya untuk supervisor
        $user = User::findOrFail($nik);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $nik) {
        $this->authorizeSupervisor(); // batasi hanya untuk supervisor
        $user = User::findOrFail($nik);

        $request->validate([
            'nama' => 'required',
            'role' => 'required|in:Admin,Kasir,Customer',
        ]);

        $data = [
            'nama' => $request->nama,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($nik) {
        $this->authorizeSupervisor(); // batasi hanya untuk supervisor
        $user = User::findOrFail($nik);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }

    private function authorizeSupervisor()
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Akses hanya untuk Supervisor.');
        }
    }
}
