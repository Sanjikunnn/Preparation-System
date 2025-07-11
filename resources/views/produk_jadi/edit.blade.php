@extends('layouts.app')

@section('title', 'Halaman Edit Produk Jadi')

@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="bg-white shadow-md rounded p-6 max-w-2xl mx-auto">
    <h2 class="text-xl font-semibold mb-4">📝 {{ isset($produk) ? 'Edit' : 'Tambah' }} Produk Jadi</h2>

    <form action="{{ isset($produk) ? route('produk_jadi.update', $produk->id) : route('produk_jadi.store') }}" method="POST" class="space-y-4">
      @csrf
      @if(isset($produk)) @method('PUT') @endif

      <div>
        <label class="block mb-1 font-medium">Nama Produk</label>
        <input type="text" name="nama_produk_jadi" value="{{ $produk->nama_produk_jadi ?? '' }}"
          class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
      </div>

      <div>
        <label class="block mb-1 font-medium">Komponen 1</label>
        <select name="id_komponen_produk_jadi_1" class="w-full border rounded px-3 py-2">
          @foreach($komponens as $komp)
          <option value="{{ $komp->id }}" @selected(isset($produk) && $produk->id_komponen_produk_jadi_1 == $komp->id)>
            {{ $komp->nama_komponen_produk_jadi }}
          </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block mb-1 font-medium">Komponen 2</label>
        <select name="id_komponen_produk_jadi_2" class="w-full border rounded px-3 py-2">
          @foreach($komponens as $komp)
          <option value="{{ $komp->id }}" @selected(isset($produk) && $produk->id_komponen_produk_jadi_2 == $komp->id)>
            {{ $komp->nama_komponen_produk_jadi }}
          </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block mb-1 font-medium">Komponen 3</label>
        <select name="id_komponen_produk_jadi_3" class="w-full border rounded px-3 py-2">
          @foreach($komponens as $komp)
          <option value="{{ $komp->id }}" @selected(isset($produk) && $produk->id_komponen_produk_jadi_3 == $komp->id)>
            {{ $komp->nama_komponen_produk_jadi }}
          </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block mb-1 font-medium">Qty</label>
        <input type="number" name="qty" value="{{ $produk->qty ?? '' }}"
          class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
      </div>

      <div class="pt-2">
        <button type="submit"
          class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition transform hover:scale-105">
          💾 Simpan
        </button>
        <a href="{{ route('produk_jadi.index') }}"
          class="ml-2 text-sm text-gray-600 hover:underline hover:text-blue-600">← Kembali</a>
      </div>
    </form>
  </div>
</div>
@endsection
