@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="container mx-auto px-4 py-6">
  <h2 class="text-xl font-semibold mb-4">➕ Tambah User</h2>

  @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      <ul class="list-disc ml-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('users.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
    @include('users._form')
  </form>
</div>
@endsection
