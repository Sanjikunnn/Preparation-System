<?php

namespace App\Http\Controllers;

use App\Models\HasilUpper;
use App\Models\KomponenProdukJadi;
use App\Models\ProdukJadi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use DNS1D;

class HasilUpperController extends Controller
{
    public function __construct()
    {
        // ⛔️ Semua method dalam controller ini hanya bisa diakses user yang login
        $this->middleware('auth');
    }

public function index(Request $request)
{
    $query = HasilUpper::with([
        'komponen1',
        'komponen2',
        'komponen3',
        'produkJadi',
        'distributeRelation'
    ]);

    // Filter barcode
    if ($request->filled('search')) {
        $query->where('no_barcode', 'like', '%' . $request->search . '%');
    }

    // Filter tanggal
    if ($request->filled('from') && $request->filled('to')) {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();
        $query->whereBetween('waktu_proses', [$from, $to]);
    }

    // Ambil hasil untuk tabel utama
    $hasil = $query->clone()->latest()->paginate(10)->appends($request->query());

    // Rekap total proses per produk jadi berdasarkan query yang sama
    $totalProsesPerProduk = $query->clone()
        ->selectRaw('id_produk_jadi, SUM(total_proses) as total')
        ->groupBy('id_produk_jadi')
        ->pluck('total', 'id_produk_jadi');
    // Rekap total hasil uppers yang sudah di-distribute
    $totalProsesDidistribusi = $query->clone()
        ->whereHas('distributeRelation') // hanya yang punya relasi distribute
        ->selectRaw('id_produk_jadi, SUM(total_proses) as total')
        ->groupBy('id_produk_jadi')
        ->pluck('total', 'id_produk_jadi');

    return view('hasil_upper.index', compact('hasil', 'totalProsesPerProduk', 'totalProsesDidistribusi'));

}


    public function create()
    {
        $komponens = KomponenProdukJadi::all();
        $produkJadiList = \App\Models\ProdukJadi::with([
            'komponen1:id,nama_komponen_produk_jadi',
            'komponen2:id,nama_komponen_produk_jadi',
            'komponen3:id,nama_komponen_produk_jadi',
        ])->get();

        return view('hasil_upper.create', compact('komponens', 'produkJadiList'));
    }

public function store(Request $request)
{
    $request->validate([
        'id_komponen_1' => 'required',
        'id_komponen_2' => 'required',
        'id_komponen_3' => 'required',
        'total_proses' => 'required|numeric',
    ]);

    // Cari Produk Jadi berdasarkan 3 komponen
    $produk = ProdukJadi::where([
        ['id_komponen_produk_jadi_1', $request->id_komponen_1],
        ['id_komponen_produk_jadi_2', $request->id_komponen_2],
        ['id_komponen_produk_jadi_3', $request->id_komponen_3],
    ])->first();

    if (!$produk) {
        return back()->with('error', 'Produk Jadi tidak ditemukan, Perhatikan Inputan Komponen !!!');
    }

    $waktu = Carbon::now();

    // Simpan data awal tanpa barcode
    $hasil = HasilUpper::create([
        'id_komponen_1' => $request->id_komponen_1,
        'id_komponen_2' => $request->id_komponen_2,
        'id_komponen_3' => $request->id_komponen_3,
        'id_produk_jadi' => $produk->id,
        'total_proses' => $request->total_proses,
        'waktu_proses' => $waktu,
        'no_barcode' => 'U-temp', // sementara
    ]);

    // Generate kode barcode
    $barcode = 'U-' . $request->id_komponen_1 . '-' . $request->id_komponen_2 . '-' . $request->id_komponen_3 . '-' . $produk->id . '-' . $waktu->format('YmdHis') . '-' . $hasil->id;

    // Generate barcode sebagai gambar PNG
    $filename = 'barcode_' . $hasil->id . '.png';
    $path = public_path('barcodes/' . $filename);

    $barcodeBase64 = DNS1D::getBarcodePNG($barcode, 'C128', 2, 60, [0, 0, 0], true);
    $barcodeImage = base64_decode($barcodeBase64);
    file_put_contents($path, $barcodeImage);

    // Cek apakah file benar-benar berhasil dibuat
    if (!file_exists($path)) {
        return back()->with('error', 'Gagal menyimpan file barcode.');
    }

    // Hitung total upper untuk produk jadi terkait
    $totalUpper = HasilUpper::where('id_produk_jadi', $produk->id)->sum('total_proses');

    // Simpan total upper tersebut ke baris yang baru saja dibuat
    $hasil->update([
        'no_barcode' => $barcode,
        'barcode_image' => 'barcodes/' . $filename,
        'total_uppers' => $totalUpper,
    ]);


    return redirect()->route('hasil_upper.index')->with('success', 'Data hasil upper berhasil disimpan.');
}

public function export(Request $request)
{
    $filename = 'hasil_upper_export_' . now()->format('Ymd_His') . '.xls';

    $query = HasilUpper::with(['komponen1', 'komponen2', 'komponen3', 'produkJadi', 'distributeRelation']);

    // 🔍 Filter berdasarkan barcode
    if ($request->filled('search')) {
        $query->where('no_barcode', 'like', '%' . $request->search . '%');
    }

    // 📅 Filter tanggal dari dan sampai
    if ($request->filled('from') && $request->filled('to')) {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();
        $query->whereBetween('waktu_proses', [$from, $to]);
    }

    $hasil = $query->latest()->get();

    // 🔢 Rekap Total per Produk Jadi
    $totalProsesPerProduk = HasilUpper::select('id_produk_jadi', DB::raw('SUM(total_proses) as total'))
        ->when($request->filled('from') && $request->filled('to'), function ($q) use ($request) {
            $q->whereBetween('waktu_proses', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay()
            ]);
        })
        ->groupBy('id_produk_jadi')
        ->pluck('total', 'id_produk_jadi');

    $totalProsesDidistribusi = HasilUpper::whereHas('distributeRelation')
        ->when($request->filled('from') && $request->filled('to'), function ($q) use ($request) {
            $q->whereBetween('waktu_proses', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay()
            ]);
        })
        ->select('id_produk_jadi', DB::raw('SUM(total_proses) as total'))
        ->groupBy('id_produk_jadi')
        ->pluck('total', 'id_produk_jadi');

    // 📄 Headers
    $headers = [
        "Content-type" => "application/vnd.ms-excel",
        "Content-Disposition" => "attachment; filename=$filename",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    // 📤 Content
    $content = '
    <html><head><meta charset="UTF-8"></head><body>
    <h3>Data Proses Hasil Upper</h3>
    <table border="1">
        <thead>
            <tr style="background-color:#f3f3f3;">
                <th>ID</th>
                <th>Komponen 1</th>
                <th>Komponen 2</th>
                <th>Komponen 3</th>
                <th>Produk Jadi</th>
                <th>Waktu Upper</th>
                <th>Barcode</th>
                <th>Barcode Gambar</th>
                <th>Qty</th>
                <th>Distribute</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($hasil as $item) {
        $content .= '<tr>';
        $content .= '<td>' . $item->id . '</td>';
        $content .= '<td>' . ($item->komponen1->nama_komponen_produk_jadi ?? '-') . '</td>';
        $content .= '<td>' . ($item->komponen2->nama_komponen_produk_jadi ?? '-') . '</td>';
        $content .= '<td>' . ($item->komponen3->nama_komponen_produk_jadi ?? '-') . '</td>';
        $content .= '<td>' . ($item->produkJadi->nama_produk_jadi ?? '-') . '</td>';
        $content .= '<td>' . $item->waktu_proses . '</td>';
        $content .= '<td>' . $item->no_barcode . '</td>';
        $content .= '<td><img src="' . asset($item->barcode_image) . '" width="150"></td>';
        $content .= '<td>' . $item->total_proses . '</td>';
        $content .= '<td>' . ($item->distributeRelation ? '✅' : '❌') . '</td>';
        $content .= '</tr>';
    }

    $content .= '</tbody></table>';

    // 🧾 Rekap Total Produk
    $content .= '<br><h3>Total Proses Hasil Upper per Produk Jadi</h3>';
    $content .= '<table border="1">
        <thead>
            <tr style="background-color:#f3f3f3;">
                <th>Nama Produk Jadi</th>
                <th>Total Hasil Uppers</th>
                <th>Total Terdistribusi</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($totalProsesPerProduk as $produkId => $total) {
        $namaProduk = ProdukJadi::find($produkId)->nama_produk_jadi ?? '-';
        $totalDistribusi = $totalProsesDidistribusi[$produkId] ?? 0;
        $content .= '<tr>';
        $content .= '<td>' . strtoupper($namaProduk) . '</td>';
        $content .= '<td align="center">' . number_format($total) . '</td>';
        $content .= '<td align="center">' . number_format($totalDistribusi) . '</td>';
        $content .= '</tr>';
    }

    if ($totalProsesPerProduk->isEmpty()) {
        $content .= '<tr><td colspan="3" align="center"><i>Tidak ada data rekap ditemukan.</i></td></tr>';
    }

    $content .= '</tbody></table>';

    $content .= '</body></html>';

    return response($content, 200, $headers);
}



}
