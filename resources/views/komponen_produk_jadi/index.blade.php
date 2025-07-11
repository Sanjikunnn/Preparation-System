@extends('layouts.app')

@section('title', 'Halaman Data Komponen Produk')

@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold">📦 Data Komponen Produk</h2>
    <a href="{{ route('komponen_produk_jadi.create') }}" class="bg-blue-200 text-black px-4 py-2 rounded transition transform hover:bg-blue-500 hover:scale-105">
      ➕ Tambah Komponen
    </a>
  </div>

  {{-- Search --}}
  <form method="GET" action="{{ route('komponen_produk_jadi.index') }}" class="mb-4 flex gap-2">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari komponen..." class="border rounded px-3 py-2 w-1/3">
    <button type="submit" class="bg-gray-200 text-black px-3 py-2 rounded transition transform hover:bg-gray-400 hover:scale-105">
      🔍 Cari
    </button>
  </form>

  {{-- Tabel --}}
  <div class="bg-white shadow-md rounded overflow-x-auto">
    <table class="min-w-full table-auto border-collapse">
      <thead class="bg-gray-100 text-gray-700 text-sm">
        <tr class="hover:bg-blue-80 text-center transition duration-200">
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Nama Komponen</th>
          <th class="border px-4 py-2">Qty</th>
          <th class="border px-4 py-2 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        @forelse ($komponens as $item)
        <tr class="hover:bg-gray-50">
          <td class="border px-4 py-2">{{ $item->id }}</td>
          <td class="border px-4 py-2">{{ $item->nama_komponen_produk_jadi }}</td>
          <td class="border px-4 py-2">{{ $item->qty }}</td>
          <td class="border px-4 py-2 text-center">
            <div class="flex justify-center gap-2">
              <a href="{{ route('komponen_produk_jadi.edit', $item->id) }}"
                class="bg-yellow-300 text-black px-3 py-1 rounded transition transform hover:bg-yellow-500 hover:scale-105">
                ✏️ Edit
              </a>
              <form action="{{ route('komponen_produk_jadi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="bg-red-300 text-black px-3 py-1 rounded transition transform hover:bg-red-500 hover:scale-105">
                  🗑️ Hapus
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="text-center text-gray-500 py-4">Tidak ada data ditemukan.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="mt-4">
    {{ $komponens->withQueryString()->links() }}
  </div>
</div>
@endsection
