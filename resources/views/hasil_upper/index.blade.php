@extends('layouts.app')

@section('title', 'Halaman Data Hasil Upper')

@section('content')
{{-- Tampilkan notifikasi sukses --}}
  @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ session('success') }}
    </div>
  @endif

  {{-- Tampilkan error umum --}}
  @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ session('error') }}
    </div>
  @endif

  {{-- Tampilkan error validasi jika ada --}}
  @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      <ul class="list-disc ml-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
<div class="container mx-auto px-4 py-6">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold">📦 Data Hasil Upper</h2>
    <div class="flex gap-2">
      <a href="{{ route('hasil_upper.export', request()->query()) }}"
        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition inline-block">
        📁 Export Excel
      </a>
      <a href="{{ route('hasil_upper.create') }}"
        class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-500 hover:scale-105 transition">
        ➕ Tambah
      </a>
    </div>
  </div>


  {{-- Search & Filter --}}
  <form method="GET" class="flex flex-wrap gap-3 items-end mb-6">
    <div>
      <label class="block text-sm mb-1">Cari Barcode</label>
      <input type="text" name="search" value="{{ request('search') }}"
        class="border px-3 py-2 rounded w-64" placeholder="Masukkan barcode...">
    </div>
    <div>
      <label class="block text-sm mb-1">Dari Tanggal</label>
      <input type="date" name="from" value="{{ request('from') }}" class="border px-3 py-2 rounded">
    </div>
    <div>
      <label class="block text-sm mb-1">Sampai Tanggal</label>
      <input type="date" name="to" value="{{ request('to') }}" class="border px-3 py-2 rounded">
    </div>
    <div>
      <button type="submit"
        class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">
        🔍 Cari
      </button>
    </div>
    <div>
      <a href="{{ route('hasil_upper.index') }}"
        class="text-sm text-blue-600 hover:underline">🔄 Reset</a>
    </div>
  </form>

  {{-- 🔢 ERP Style Rekap Total Proses per Produk Jadi --}}
  <div class="bg-white border border-gray-600 shadow-sm rounded-lg mb-6">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
        <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" stroke-width="1.5"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 3h18M3 9h18M3 15h18M3 21h18" />
        </svg>
        Total Proses Hasil Upper per Produk Jadi
      </h3>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left border-separate border-spacing-y-1 px-6 py-3">
        <thead class="text-xs text-gray-800 uppercase tracking-wider">
          <tr>
            <th class="px-6 py-3 bg-gray-300 rounded-l-md">Nama Produk Jadi</th>
            <th class="px-6 py-3 bg-gray-300 text-center">Total Hasil Uppers</th>
            <th class="px-6 py-3 bg-gray-300 text-center rounded-r-md">Total Terdistribusi</th>
          </tr>
        </thead>
        <tbody class="text-gray-800">
          @forelse ($totalProsesPerProduk as $produkId => $total)
          <tr class="bg-white hover:bg-gray-50 rounded-md shadow-sm">
            <td class="px-6 py-3 rounded-l-md uppercase font-medium">
              {{ \App\Models\ProdukJadi::find($produkId)->nama_produk_jadi ?? '-' }}
            </td>
            <td class="px-6 py-3 text-center">{{ number_format($total) }}</td>
            <td class="px-6 py-3 text-center rounded-r-md">
              {{ number_format($totalProsesDidistribusi[$produkId] ?? 0) }}
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="3" class="px-6 py-4 text-center text-gray-600 italic">Tidak ada data rekap ditemukan.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>



  <div class="overflow-x-auto bg-white shadow-md rounded">
    <table class="min-w-full table-auto text-sm border border-gray-300">
      <thead class="bg-gray-100 text-gray-700 text-center">
        <tr>
          <th class="px-2 py-2 border">ID</th>
          <th class="px-2 py-2 border">Komp. 1</th>
          <th class="px-2 py-2 border">Komp. 2</th>
          <th class="px-2 py-2 border">Komp. 3</th>
          <th class="px-2 py-2 border">Produk</th>
          <th class="px-2 py-2 border">Waktu Proses</th>
          <th class="px-2 py-2 border">Barcode Upper</th>
          <th class="px-2 py-2 border">Barcode Gambar</th>
          <th class="px-2 py-2 border">Qty Proses</th>
          <th class="px-2 py-2 border">Distribute</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($hasil as $item)
        <tr class="hover:bg-gray-50 transition">
          <td class="border px-2 py-2">{{ $item->id }}</td>
          <td class="border px-2 py-2">{{ $item->komponen1->nama_komponen_produk_jadi ?? '-' }}</td>
          <td class="border px-2 py-2">{{ $item->komponen2->nama_komponen_produk_jadi ?? '-' }}</td>
          <td class="border px-2 py-2">{{ $item->komponen3->nama_komponen_produk_jadi ?? '-' }}</td>
          <td class="border px-2 py-2">{{ $item->produkJadi->nama_produk_jadi ?? '-' }}</td>
          <td class="border px-2 py-2">{{ $item->waktu_proses }}</td>
          <td class="border px-2 py-2">
            <div class="flex items-center gap-2">
              <input type="text" id="barcode{{ $item->id }}" value="{{ $item->no_barcode }}" readonly
                class="w-full px-2 py-1 border rounded bg-gray-100 text-xs">
              <button onclick="copyToClipboard('barcode{{ $item->id }}')"
                class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition text-xs">📋</button>
            </div>
          </td>
          <td class="border px-2 py-2 text-center">
            <img src="{{ asset($item->barcode_image) }}" width="150" class="mb-1 mx-auto rounded shadow">
            <a href="{{ asset($item->barcode_image) }}" download class="text-sm text-blue-600 hover:underline">⬇️ Unduh</a>
          </td>
          <td class="border px-2 py-2 text-center">{{ $item->total_proses }}</td>
          <td class="border px-2 py-2 text-center">
            @if($item->distribute)
              ✅ <a href="{{ route('distribute.index') }}" class="text-blue-600 hover:underline">Lihat</a>
            @else
              ❌
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="10" class="text-center text-gray-500 py-4">Tidak ada data ditemukan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- 📄 Paginasi --}}
  <div class="mt-4">
    {{ $hasil->withQueryString()->links() }}
  </div>
</div>
@endsection

@section('scripts')
<script>
  function copyToClipboard(id) {
    const copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    alert("Barcode disalin: " + copyText.value);
  }
</script>
@endsection
