<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\KomponenProdukJadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomponenProdukJadiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $komponens = KomponenProdukJadi::when($search, function ($query, $search) {
            return $query->where('nama_komponen_produk_jadi', 'like', '%' . $search . '%');
        })->orderBy('id', 'asc')->paginate(10);

        return view('komponen_produk_jadi.index', compact('komponens', 'search'));
    }


    public function create()
    {
        return view('komponen_produk_jadi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_komponen_produk_jadi' => 'required|string|max:255',
            'qty' => 'required|numeric|min:1',
        ]);

        KomponenProdukJadi::create([
            'nama_komponen_produk_jadi' => $request->nama_komponen_produk_jadi,
            'qty' => $request->qty,
        ]);

        return redirect()->route('komponen_produk_jadi.index')->with('success', 'Komponen berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $this->authorizeSupervisor();
        $komponen = KomponenProdukJadi::findOrFail($id);
        return view('komponen_produk_jadi.edit', compact('komponen'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeSupervisor();
        $request->validate([
            'nama_komponen_produk_jadi' => 'required|string|max:255',
            'qty' => 'required|numeric|min:1',
        ]);

        $komponen = KomponenProdukJadi::findOrFail($id);
        $komponen->update([
            'nama_komponen_produk_jadi' => $request->nama_komponen_produk_jadi,
            'qty' => $request->qty,
        ]);

        return redirect()->route('komponen_produk_jadi.index')->with('success', 'Komponen berhasil diupdate.');
    }


    public function destroy($id)
    {
        $this->authorizeSupervisor();
        KomponenProdukJadi::destroy($id);
        return redirect()->route('komponen_produk_jadi.index');
    }

    // Fungsi pembantu untuk validasi role supervisor
    private function authorizeSupervisor()
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Akses hanya untuk Supervisor.');
        }
    }
}
