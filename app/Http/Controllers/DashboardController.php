<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilUpper;
use App\Models\Distribute;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ProdukJadi;

class DashboardController extends Controller
{
    public function index()
    {
        $upperData = HasilUpper::select(
            DB::raw('DATE(waktu_proses) as raw_tanggal'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('raw_tanggal')
        ->orderBy('raw_tanggal', 'asc')
        ->limit(7)
        ->get()
        ->map(function ($item) {
            $item->tanggal = \Carbon\Carbon::parse($item->raw_tanggal)->format('d/m/Y');
            return $item;
        });


        $distributeData = Distribute::select(
            DB::raw('DATE(waktu_proses) as raw_tanggal'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('raw_tanggal')
        ->orderBy('raw_tanggal', 'asc')
        ->limit(7)
        ->get()
        ->map(function ($item) {
            $item->tanggal = \Carbon\Carbon::parse($item->raw_tanggal)->format('d/m/Y');
            return $item;
        });

        $produkUpperData = HasilUpper::with('produkJadi')
            ->select('id_produk_jadi', DB::raw('COUNT(*) as total'))
            ->groupBy('id_produk_jadi')
            ->get();

        $produkDistributeData = Distribute::with('hasilUpper.produkJadi')
            ->select('id_hasil_upper', DB::raw('COUNT(*) as total'))
            ->groupBy('id_hasil_upper') // ✅ tambahkan ini
            ->get()
            ->groupBy(function ($item) {
                return optional($item->hasilUpper->produkJadi)->nama_produk_jadi ?? 'Tidak Diketahui';
            })
            ->map(function ($group) {
                return $group->sum('total');
            });

        return view('dashboard.index', compact('upperData', 'distributeData', 'produkUpperData', 'produkDistributeData'));
    }
}
