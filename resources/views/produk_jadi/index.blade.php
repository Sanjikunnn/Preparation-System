@extends('layouts.app')

@section('title', 'Halaman Data Produk Jadi')

@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold">🧩 Data Produk Jadi</h2>
    <a href="{{ route('produk_jadi.create') }}"
      class="bg-blue-300 text-black px-4 py-2 rounded hover:bg-blue-500 transition transform hover:scale-105">
      ➕ Tambah Produk Jadi
    </a>
  </div>

  <div class="bg-white shadow-md rounded overflow-x-auto">
    <table class="min-w-full table-auto border-collapse">
      <thead class="bg-gray-100 text-gray-700 text-center text-sm">
        <tr>
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Nama Produk</th>
          <th class="border px-3 py-2">Komponen 1</th>
          <th class="border px-4 py-2">Komponen 2</th>
          <th class="border px-4 py-2">Komponen 3</th>
          <th class="border px-4 py-2">Qty</th>
          <th class="border px-4 py-2">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        @foreach ($produks as $item)
        <tr class="hover:bg-gray-50 transition">
          <td class="border px-4 py-2">{{ $item->id }}</td>
          <td class="border px-4 py-2">{{ $item->nama_produk_jadi }}</td>
          <td class="border px-4 py-2">{{ $item->komponen1->nama_komponen_produk_jadi ?? '-' }}</td>
          <td class="border px-4 py-2">{{ $item->komponen2->nama_komponen_produk_jadi ?? '-' }}</td>
          <td class="border px-4 py-2">{{ $item->komponen3->nama_komponen_produk_jadi ?? '-' }}</td>
          <td class="border px-4 py-2">{{ $item->qty }}</td>
          <td class="border px-4 py-2 text-center">
            <div class="flex justify-center gap-2">
              <a href="{{ route('produk_jadi.edit', $item->id) }}"
                class="bg-yellow-300 text-black px-3 py-1 rounded hover:bg-yellow-400 transition hover:scale-105">✏️ Edit</a>
              <form action="{{ route('produk_jadi.destroy', $item->id) }}" method="POST"
                onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="bg-red-300 text-black px-3 py-1 rounded hover:bg-red-400 transition hover:scale-105">🗑️
                  Hapus</button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach

        @if ($produks->isEmpty())
        <tr>
          <td colspan="7" class="text-center text-gray-500 py-4">Tidak ada data ditemukan.</td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>
@endsection
