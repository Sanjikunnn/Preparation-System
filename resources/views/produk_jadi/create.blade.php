@extends('layouts.app')

@section('title', 'Halaman Input Produk Jadi')

@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="bg-white shadow-md rounded p-6 max-w-2xl mx-auto">
    <h2 class="text-xl font-semibold mb-4">➕ Input Produk Jadi</h2>

    <form action="{{ isset($produk) ? route('produk_jadi.update', $produk->id) : route('produk_jadi.store') }}" method="POST" class="space-y-4">
      @csrf
      @if(isset($produk)) @method('PUT') @endif

      <div>
        <label class="block font-medium mb-1">Nama Produk</label>
        <input type="text" name="nama_produk_jadi" value="{{ $produk->nama_produk_jadi ?? '' }}"
          class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-400" required>
      </div>

      @for ($i = 1; $i <= 3; $i++)
      <div>
        <label class="block font-medium mb-1">Komponen {{ $i }}</label>
        <select name="id_komponen_produk_jadi_{{ $i }}"
          class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-400" required>
          @foreach ($komponens as $komp)
            <option value="{{ $komp->id }}"
              @selected(isset($produk) && $produk->{'id_komponen_produk_jadi_'.$i} == $komp->id)>
              {{ $komp->nama_komponen_produk_jadi }}
            </option>
          @endforeach
        </select>
      </div>
      @endfor

      <div>
        <label class="block font-medium mb-1">Qty</label>
        <input type="number" name="qty" value="{{ $produk->qty ?? '' }}" min="1"
          class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-400" required>
      </div>

      <div class="pt-2">
        <button type="submit"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 hover:scale-105 transition transform">
          💾 Simpan
        </button>
        <a href="{{ route('produk_jadi.index') }}"
          class="ml-3 text-sm text-gray-600 hover:text-blue-600 hover:underline transition">
          ← Kembali
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
