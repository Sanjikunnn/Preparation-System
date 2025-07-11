@extends('layouts.app')

@section('title', 'Halaman Data Distribusi Produk')

@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold">🚚 Data Distribusi Produk</h2>
    <a href="{{ route('distribute.export', request()->query()) }}"
       class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
       📁 Export CSV
    </a>
  </div>

  {{-- Notifikasi --}}
  @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
      {{ session('success') }}
    </div>
  @endif
  @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ session('error') }}
    </div>
  @endif

  {{-- Form Input Barcode --}}
  <form action="{{ route('distribute.store') }}" method="POST" class="flex gap-2 mb-6">
    @csrf
    <input type="text" name="no_barcode" placeholder="Scan / Input Barcode Upper"
      class="border rounded px-3 py-2 w-1/2" autofocus>
    <button type="submit"
      class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
      🚀 Distribusikan (FIFO)
    </button>
  </form>

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
      <a href="{{ route('distribute.index') }}"
        class="text-sm text-blue-600 hover:underline">🔄 Reset</a>
    </div>
  </form>

  {{-- 🔢 ERP Style Rekap Total Proses per Produk Jadi --}}
  <div class="bg-white border border-gray-200 shadow-sm rounded-lg mb-6">
    <div class="px-2 py-2 border-b border-gray-200 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 3h18M3 9h18M3 15h18M3 21h18" />
        </svg>
        Total Distribute Hasil Upper per Produk Jadi
      </h3>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm text-left border-separate border-spacing-y-1 px-6 py-3">
        <thead class="text-xs text-gray-800 uppercase tracking-wider">
          <tr>
            <th class="px-6 py-3 bg-gray-400 rounded-l-md">Nama Produk Jadi</th>
            <th class="px-6 py-3 bg-gray-400 text-center">Total distribusi</th>
            <th class="px-6 py-3 bg-gray-400 text-center rounded-r-md">Total Hasil Uppers</th>
          </tr>
        </thead>
        <tbody class="text-gray-700">
          @forelse ($totalProsesPerProduk as $produkId => $total)
          <tr class="bg-white hover:bg-gray-50 rounded-md shadow-sm">
            <td class="px-6 py-3 rounded-l-md uppercase font-medium">
              {{ \App\Models\ProdukJadi::find($produkId)->nama_produk_jadi ?? '-' }}
            </td>
            <td class="px-6 py-3 text-center">
              {{ number_format($totalProsesDidistribusi[$produkId] ?? 0) }}
            </td>
            <td class="px-6 py-3 text-center rounded-r-md">{{ number_format($total) }}</td>
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

  {{-- Tabel Distribusi --}}
  <div class="overflow-x-auto bg-white shadow-md rounded">
    <table class="min-w-full table-auto text-sm border border-gray-300">
      <thead class="bg-gray-100 text-gray-700 text-center">
        <tr>
          <th class="border px-2 py-2">ID</th>
          <th class="border px-2 py-2">Waktu Distribute</th>
          <th class="border px-2 py-2">Barcode Distribute</th>
          <th class="border px-2 py-2">Produk</th>
          <th class="border px-2 py-2">Barcode Gambar</th>
          <th class="border px-2 py-2">Unduh</th>
          <th class="border px-2 py-2">Qty</th>
          <th class="border px-2 py-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($distributes as $d)
        <tr class="hover:bg-gray-50 transition">
          <td class="border px-2 py-2">{{ $d->id }}</td>
          <td class="border px-2 py-2">{{ $d->waktu_proses }}</td>
          <td class="border px-2 py-2">
            <div class="flex items-center gap-2">
              <input type="text" id="barcode{{ $d->id }}" value="{{ $d->no_barcode }}" readonly
                class="w-full px-2 py-1 border rounded bg-gray-100 text-xs">
              <button onclick="copyToClipboard('barcode{{ $d->id }}')"
                class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition text-xs">📋</button>
            </div>
          </td>
          <td class="border px-2 py-2">{{ $d->hasilUpper->produkJadi->nama_produk_jadi ?? '-' }}</td>
          <td class="border px-2 py-2 text-center">
            <img src="{{ asset($d->barcode_image) }}" width="150" class="mb-1 mx-auto rounded shadow">
          </td>
          <td class="border px-2 py-2 text-center">
            <a href="{{ asset($d->barcode_image) }}" download class="text-sm text-blue-600 hover:underline">⬇️ Unduh</a>
          </td>
          <td class="border px-2 py-2 text-center">{{ $d->hasilUpper->total_proses }}</td>
          <td class="border px-2 py-2 text-center">
            <form action="{{ route('distribute.destroy', $d->id) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus distribusi ini?')">
              @csrf
              @method('DELETE')
              <button type="submit"
                class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 transition text-sm">
                🗑️ Hapus
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="11" class="text-center text-gray-500 py-4">Tidak ada data ditemukan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="mt-6">
    {{ $distributes->appends(request()->query())->links() }}
  </div>
</div>

@endsection

@section('scripts')
<script>
  function copyToClipboard(id) {
    const copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value)
      .then(() => alert("Barcode disalin: " + copyText.value))
      .catch(() => alert("Gagal menyalin"));
  }
</script>
@endsection
