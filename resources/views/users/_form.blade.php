@csrf

{{-- NIK --}}
<div class="mb-4">
  <label class="block mb-1 font-medium">NIK</label>
  <input type="text" name="nik"
         value="{{ old('nik', $user->nik ?? $nextNik ?? '') }}"
         class="w-full border rounded px-3 py-2"
         autocomplete="off"
         readonly>
</div>

{{-- Nama --}}
<div class="mb-4">
  <label class="block mb-1 font-medium">Nama</label>
  <input type="text" name="nama"
         value="{{ old('nama', $user->nama ?? '') }}"
         class="w-full border rounded px-3 py-2"
         placeholder="Masukan Nama User"
         autocomplete="off">
</div>

{{-- Role --}}
<div class="mb-4">
  <label class="block mb-1 font-medium">Role</label>
  <select name="role" class="w-full border rounded px-3 py-2" autocomplete="off">
    <option value="">-- Pilih Role --</option>
    <option value="Operator" {{ old('role', $user->role ?? '') == 'Operator' ? 'selected' : '' }}>Operator</option>
    <option value="Supervisor" {{ old('role', $user->role ?? '') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
  </select>
</div>

{{-- Password --}}
<div class="mb-4">
  <label class="block mb-1 font-medium">Password</label>
  <input type="password" name="password"
         class="w-full border rounded px-3 py-2"
         placeholder="{{ isset($user) ? 'Kosongkan jika tidak ingin diubah' : 'Masukkan Password' }}"
         autocomplete="new-password">
</div>

<button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
  💾 Simpan
</button>
