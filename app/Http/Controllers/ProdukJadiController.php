<?php

namespace App\Http\Controllers;

use App\Models\ProdukJadi;
use App\Models\KomponenProdukJadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukJadiController extends Controller
{
    public function __construct()
    {
        // ⛔️ Semua method dalam controller ini hanya bisa diakses user yang login
        $this->middleware('auth');
    }
    public function index()
    {
        $produks = ProdukJadi::with(['komponen1', 'komponen2', 'komponen3'])->get();
        return view('produk_jadi.index', compact('produks'));
    }

    public function create()
    {
        $komponens = KomponenProdukJadi::all();
        return view('produk_jadi.create', compact('komponens'));
    }

    public function store(Request $request)
    {
        ProdukJadi::create($request->all());
        return redirect()->route('produk_jadi.index');
    }

    public function edit($id)
    {
        $this->authorizeSupervisor();
        $produk = ProdukJadi::findOrFail($id);
        $komponens = KomponenProdukJadi::all();
        return view('produk_jadi.edit', compact('produk', 'komponens'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeSupervisor();
        $produk = ProdukJadi::findOrFail($id);
        $produk->update($request->all());
        return redirect()->route('produk_jadi.index');
    }

    public function destroy($id)
    {
        $this->authorizeSupervisor();
        ProdukJadi::destroy($id);
        return redirect()->route('produk_jadi.index');
    }
    // Fungsi pembantu untuk validasi role supervisor
    private function authorizeSupervisor()
    {
        if (Auth::user()->role !== 'supervisor') {
            abort(403, 'Akses hanya untuk Supervisor.');
        }
    }
}
