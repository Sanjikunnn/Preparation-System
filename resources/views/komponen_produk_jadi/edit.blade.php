@extends('layouts.app')

@section('title', 'Halaman Edit Komponen Produk')

@section('content')
<div class="max-w-xl mx-auto mt-8 p-6 bg-white rounded shadow">
  <h2 class="text-xl font-semibold mb-4">✏️ Edit Komponen Produk</h2>

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

  <form action="{{ route('komponen_produk_jadi.update', $komponen->id) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium mb-1">Nama Komponen:</label>
      <input type="text" name="nama_komponen_produk_jadi" value="{{ old('nama_komponen_produk_jadi', $komponen->nama_komponen_produk_jadi) }}"
        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" required>
    </div>

    <div>
      <label class="block text-sm font-medium mb-1">Qty:</label>
      <input type="number" name="qty" value="{{ old('qty', $komponen->qty) }}"
        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" required>
    </div>

    <div class="flex justify-end">
      <button type="submit"
        class="bg-yellow-500 text-black px-4 py-2 mr-2 rounded transition transform hover:bg-yellow-600 hover:scale-105">
        💾 Update
      </button>
      <a href="{{ route('komponen_produk_jadi.index') }}"
        class="bg-gray-300 text-black px-2 py-2 rounded transition transform hover:bg-gray-400 hover:scale-105 inline-block">
        ← Kembali ke daftar
      </a>


    </div>
  </form>
</div>
@endsection
