<?php

namespace App\Http\Controllers;

use App\Models\Distribute;
use App\Models\HasilUpper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use DNS1D;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // tambahkan kalau belum


class DistributeController extends Controller
{
    public function __construct()
    {
        // ⛔️ Semua method dalam controller ini hanya bisa diakses user yang login
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Ambil data distribusi berdasarkan filter
        $distributes = Distribute::with([
                'hasilUpper.komponen1',
                'hasilUpper.komponen2',
                'hasilUpper.komponen3',
                'hasilUpper.produkJadi'
            ])
            ->when($request->search, function ($q) use ($request) {
                $q->where('no_barcode', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('from') && $request->filled('to'), function ($q) use ($request) {
                $q->whereBetween('waktu_proses', [
                    Carbon::parse($request->from)->startOfDay(),
                    Carbon::parse($request->to)->endOfDay()
                ]);
            })
            ->latest()
            ->paginate(10)
            ->appends($request->query());

        // Ambil ID hasil_upper dari distribute yang terfilter
        $filteredDistributeIds = Distribute::when($request->search, function ($q) use ($request) {
                $q->where('no_barcode', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('from') && $request->filled('to'), function ($q) use ($request) {
                $q->whereBetween('waktu_proses', [
                    Carbon::parse($request->from)->startOfDay(),
                    Carbon::parse($request->to)->endOfDay()
                ]);
            })
            ->pluck('id_hasil_upper');

        // Ambil hasil_upper yang hanya digunakan dalam distribute terfilter
        $filteredHasilUpper = HasilUpper::whereIn('id', $filteredDistributeIds);

        // Total proses per produk jadi (mengikuti distribute terfilter)
        $totalProsesPerProduk = (clone $filteredHasilUpper)
            ->select('id_produk_jadi', DB::raw('SUM(total_proses) as total'))
            ->groupBy('id_produk_jadi')
            ->pluck('total', 'id_produk_jadi');

        // Total proses yang sudah terdistribusi
        $totalProsesDidistribusi = (clone $filteredHasilUpper)
            ->whereHas('distributeRelation')
            ->select('id_produk_jadi', DB::raw('SUM(total_proses) as total'))
            ->groupBy('id_produk_jadi')
            ->pluck('total', 'id_produk_jadi');

        return view('distribute.index', compact(
            'distributes',
            'totalProsesPerProduk',
            'totalProsesDidistribusi'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_barcode' => 'required',
        ]);

        // Ambil barcode dari request
        $barcodeInput = $request->no_barcode;

        // Cek apakah barcode valid
        $hasil = HasilUpper::where('no_barcode', $barcodeInput)->first();

        if (!$hasil) {
            return back()->with('error', 'Barcode tidak ditemukan dalam data hasil upper.');
        }

        if ($hasil->distribute) {
            return back()->with('error', 'Barcode ini sudah di distribusi.');
        }

        // 🔥 FIFO VALIDATION: Ambil hasil upper paling awal yang belum didistribusi
        $fifoHasil = HasilUpper::where('distribute', false)->orderBy('id')->first();

        // Cek apakah barcode yang di-scan adalah hasil upper pertama (FIFO)
        if ($hasil->id !== $fifoHasil->id) {
            return back()->with('error', 'Distribusi harus dilakukan secara FIFO. Silakan scan barcode hasil upper dengan ID paling awal terlebih dahulu.');
        }

        // ✅ Barcode valid dan sesuai FIFO
        $waktu = Carbon::now();

        $distribute = Distribute::create([
            'id_hasil_upper' => $hasil->id,
            'waktu_proses' => $waktu,
            'no_barcode' => '',
        ]);

        $hasil->distribute = true;
        $hasil->save();

        $finalBarcode = $hasil->no_barcode . '-' . str_pad($distribute->id, 3, '0', STR_PAD_LEFT);
        $filename = 'distribute_barcode_' . $distribute->id . '.png';
        $path = public_path('barcodes_distribute/' . $filename);

        // 🔥 Generate barcode dan simpan sebagai file gambar
        $barcodeBase64 = DNS1D::getBarcodePNG($finalBarcode, 'C128', 2, 60, [0, 0, 0], true);
        $barcodeImage = base64_decode($barcodeBase64);
        file_put_contents($path, $barcodeImage);

        if (file_exists($path)) {
            logger("Barcode berhasil disimpan: " . $path);
        } else {
            logger("❌ Gagal simpan barcode!");
        }

        $distribute->update([
            'no_barcode' => $finalBarcode,
            'barcode_image' => 'barcodes_distribute/' . $filename,
        ]);

        return redirect()->route('distribute.index')->with('success', 'Distribusi berhasil disimpan.');
    }


public function export(Request $request)
{
    $filename = 'distribute_export_' . now()->format('Ymd_His') . '.xls';

    $query = Distribute::with([
        'hasilUpper.produkJadi'
    ]);

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

    $distributes = $query->latest()->get();

    // Ambil id_hasil_upper terfilter
    $filteredHasilUpperIds = $distributes->pluck('id_hasil_upper');

    // Rekap total proses
    $totalProsesPerProduk = HasilUpper::whereIn('id', $filteredHasilUpperIds)
        ->select('id_produk_jadi', DB::raw('SUM(total_proses) as total'))
        ->groupBy('id_produk_jadi')
        ->pluck('total', 'id_produk_jadi');

    $totalProsesDidistribusi = HasilUpper::whereIn('id', $filteredHasilUpperIds)
        ->whereHas('distributeRelation')
        ->select('id_produk_jadi', DB::raw('SUM(total_proses) as total'))
        ->groupBy('id_produk_jadi')
        ->pluck('total', 'id_produk_jadi');

    $headers = [
        "Content-type" => "application/vnd.ms-excel",
        "Content-Disposition" => "attachment; filename=$filename",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    $content = '
    <html>
    <head>
        <meta charset="UTF-8">
    </head>
    <body>
    <h2>Data Distribusi Produk</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color:#f3f3f3;">
                <th>ID</th>
                <th>Waktu Distribute</th>
                <th>Barcode Distribute</th>
                <th>Produk</th>
                <th>Barcode Gambar</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($distributes as $d) {
        $content .= '<tr>';
        $content .= '<td>' . $d->id . '</td>';
        $content .= '<td>' . $d->waktu_proses . '</td>';
        $content .= '<td>' . $d->no_barcode . '</td>';
        $content .= '<td>' . ($d->hasilUpper->produkJadi->nama_produk_jadi ?? '-') . '</td>';
        $content .= '<td><img src="' . asset($d->barcode_image) . '" width="150"></td>';
        $content .= '<td>' . ($d->hasilUpper->total_proses ?? '-') . '</td>';
        $content .= '</tr>';
    }

    $content .= '</tbody></table>';

    // Bagian Rekap Proses Hasil Upper per Produk Jadi
    $content .= '
    <br><br>
    <h3>Total Proses Distribusi Hasil Upper per Produk Jadi</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color:#e0e0e0;">
                <th>Nama Produk Jadi</th>
                <th>Total Distribusi</th>
                <th>Total Hasil Upper</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($totalProsesPerProduk as $produkId => $totalUpper) {
        $produkName = \App\Models\ProdukJadi::find($produkId)->nama_produk_jadi ?? '-';
        $totalDistributed = $totalProsesDidistribusi[$produkId] ?? 0;

        $content .= '<tr>';
        $content .= '<td>' . $produkName . '</td>';
        $content .= '<td>' . number_format($totalDistributed) . '</td>';
        $content .= '<td>' . number_format($totalUpper) . '</td>';
        $content .= '</tr>';
    }

    $content .= '</tbody></table></body></html>';

    return response($content, 200, $headers);
}
private function authorizeSupervisor()
{
    if (Auth::user()->role !== 'supervisor') {
        abort(403, 'Akses hanya untuk Supervisor.');
    }
}


public function destroy($id)
{
    $this->authorizeSupervisor(); // batasi hanya untuk supervisor

    $distribute = Distribute::findOrFail($id);

    // Kembalikan status distribute pada HasilUpper
    $hasil = $distribute->hasilUpper;
    if ($hasil) {
        $hasil->distribute = false;
        $hasil->save();
    }

    // Hapus file barcode (jika ada)
    if ($distribute->barcode_image && file_exists(public_path($distribute->barcode_image))) {
        unlink(public_path($distribute->barcode_image));
    }

    // Hapus record distribute
    $distribute->delete();

    return redirect()->route('distribute.index')->with('success', 'Distribusi berhasil dihapus.');
}

}
