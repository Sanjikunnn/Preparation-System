@extends('layouts.app')

@section('title', 'Data User')

@section('content')
<div class="container mx-auto px-4 py-6">
  <h2 class="text-xl font-semibold mb-4">👥 Data Pengguna</h2>

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

  <div class="mb-4">
    <a href="{{ route('users.create') }}"
       class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
      ➕ Tambah User
    </a>
  </div>

  <div class="overflow-x-auto bg-white shadow rounded">
    <table class="min-w-full table-auto text-sm border border-gray-300">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="border px-4 py-2">NIK</th>
          <th class="border px-4 py-2">Nama</th>
          <th class="border px-4 py-2">Role</th>
          <th class="border px-4 py-2 text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($users as $user)
        <tr class="hover:bg-gray-50 transition">
          <td class="border px-4 py-2">{{ $user->nik }}</td>
          <td class="border px-4 py-2">{{ $user->nama }}</td>
          <td class="border px-4 py-2">{{ $user->role }}</td>
          <td class="border px-4 py-2 text-center">
            <a href="{{ route('users.edit', $user->nik) }}"
               class="text-blue-600 hover:underline text-sm">✏️ Edit</a>
            <form action="{{ route('users.destroy', $user->nik) }}" method="POST"
                  class="inline-block"
                  onsubmit="return confirm('Yakin ingin menghapus user ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-600 hover:underline text-sm">🗑️ Hapus</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="text-center text-gray-500 py-4 italic">Tidak ada data user.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
