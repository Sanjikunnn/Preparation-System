@extends('layouts.app')

@section('title', 'Halaman Input Komponen Produk')

@section('content')
<div class="max-w-xl mx-auto mt-8 p-6 bg-white rounded shadow">
  <h2 class="text-xl font-semibold mb-4">➕ Input Komponen Produk</h2>

  {{-- Tampilkan error jika validasi gagal --}}
  @if ($errors->any())
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded border border-red-300">
      <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('komponen_produk_jadi.store') }}" method="POST" class="space-y-4">
    @csrf

    <div>
      <label class="block text-sm font-medium mb-1">Nama Komponen:</label>
      <input type="text" name="nama_komponen_produk_jadi" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Qty:</label>
      <input type="number" name="qty" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" required>
    </div>

    <div class="flex justify-end">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded transition transform hover:bg-blue-700 hover:scale-105">
        💾 Simpan
      </button>
    </div>
  </form>
</div>
@endsection
