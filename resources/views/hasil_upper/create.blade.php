@extends('layouts.app')

@section('title', 'Halaman Input Hasil Upper')

@section('content')
<div class="container mx-auto px-4 py-6">
  <h2 class="text-xl font-semibold mb-4">🛠️ Input Hasil Upper</h2>

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

  {{-- 💡 Petunjuk Kombinasi Komponen yang Valid --}}
  <div class="mt-10 bg-blue-50 border border-blue-300 text-blue-800 px-4 py-3 rounded shadow-sm">
    <h3 class="text-md font-semibold mb-2">📌 Petunjuk Kombinasi Komponen yang Valid</h3>
    <p class="text-sm mb-3">Berikut daftar kombinasi komponen yang menghasilkan produk jadi tertentu:</p>

    <div class="overflow-x-auto">
      <table class="w-full text-sm border border-blue-200">
        <thead class="bg-blue-100 text-blue-900">
          <tr>
            <th class="border px-3 py-2">Produk Jadi</th>
            <th class="border px-3 py-2">Komponen 1</th>
            <th class="border px-3 py-2">Komponen 2</th>
            <th class="border px-3 py-2">Komponen 3</th>
          </tr>
        </thead>
        <tbody>
          @forelse($produkJadiList as $pj)
            <tr class="bg-white">
              <td class="border px-3 py-2 font-semibold text-gray-700">{{ $pj->nama_produk_jadi }}</td>
              <td class="border px-3 py-2">{{ $pj->komponen1->nama_komponen_produk_jadi ?? '-' }}</td>
              <td class="border px-3 py-2">{{ $pj->komponen2->nama_komponen_produk_jadi ?? '-' }}</td>
              <td class="border px-3 py-2">{{ $pj->komponen3->nama_komponen_produk_jadi ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-gray-500 py-3 italic">Belum ada data kombinasi produk jadi.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <form action="{{ route('hasil_upper.store') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow-md">
    @csrf

    {{-- Komponen 1 --}}
    <div>
      <label for="id_komponen_1" class="block mb-1 font-medium">Komponen 1</label>
      <select name="id_komponen_1" id="id_komponen_1" class="w-full border rounded px-3 py-2">
        @foreach($komponens as $k)
          <option value="{{ $k->id }}">{{ $k->nama_komponen_produk_jadi }}</option>
        @endforeach
      </select>
    </div>

    {{-- Komponen 2 --}}
    <div>
      <label for="id_komponen_2" class="block mb-1 font-medium">Komponen 2</label>
      <select name="id_komponen_2" id="id_komponen_2" class="w-full border rounded px-3 py-2">
        @foreach($komponens as $k)
          <option value="{{ $k->id }}">{{ $k->nama_komponen_produk_jadi }}</option>
        @endforeach
      </select>
    </div>

    {{-- Komponen 3 --}}
    <div>
      <label for="id_komponen_3" class="block mb-1 font-medium">Komponen 3</label>
      <select name="id_komponen_3" id="id_komponen_3" class="w-full border rounded px-3 py-2">
        @foreach($komponens as $k)
          <option value="{{ $k->id }}">{{ $k->nama_komponen_produk_jadi }}</option>
        @endforeach
      </select>
    </div>

    {{-- Total Proses --}}
    <div>
      <label for="total_proses" class="block mb-1 font-medium">Total Proses</label>
      <input type="number" name="total_proses" id="total_proses"
        class="w-full border rounded px-3 py-2" placeholder="Masukkan jumlah proses">
    </div>

    <div>
      <button type="submit"
        class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">
        ✅ Proses dan Generate Barcode
      </button>
    </div>
  </form>
</div>
@endsection
