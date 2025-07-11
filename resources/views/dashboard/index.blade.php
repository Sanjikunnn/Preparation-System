@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@if (session('success'))
  <div class="alert alert-success mb-4">
    {{ session('success') }}
  </div>
@endif

<div class="container mx-auto px-4 py-6">
  <h2 class="text-2xl font-bold mb-6 text-gray-800">📊 Dashboard Produksi Harian</h2>

  {{-- Info Ringkas --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-gradient-to-r from-green-100 to-green-50 border-l-4 border-green-500 text-green-800 p-4 rounded shadow">
      <div class="flex items-center gap-3">
        <i class="bi bi-graph-up-arrow text-xl"></i>
        <div>
          <h4 class="font-semibold">Total Upper Hari Ini</h4>
          <p class="text-sm">{{ $upperData->firstWhere('tanggal', now()->format('d/m/Y'))['total'] ?? 0 }} Proses</p>
        </div>
      </div>
    </div>
    <div class="bg-gradient-to-r from-blue-100 to-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded shadow">
      <div class="flex items-center gap-3">
        <i class="bi bi-truck text-xl"></i>
        <div>
          <h4 class="font-semibold">Total Distribusi Hari Ini</h4>
          <p class="text-sm">{{ $distributeData->firstWhere('tanggal', now()->format('d/m/Y'))['total'] ?? 0 }} Proses</p>
        </div>
      </div>
    </div>
  </div>

  <div class="grid md:grid-cols-2 gap-6">
    {{-- Grafik Hasil Upper per Hari --}}
    <div class="bg-white p-6 rounded shadow border">
      <h3 class="text-lg font-semibold mb-4 text-gray-700">🛠️ Proses Upper (7 Hari Terakhir)</h3>
      <canvas id="upperChart" height="200"></canvas>
    </div>

    {{-- Grafik Distribusi per Hari --}}
    <div class="bg-white p-6 rounded shadow border">
      <h3 class="text-lg font-semibold mb-4 text-gray-700">🚚 Distribusi Produk (7 Hari Terakhir)</h3>
      <canvas id="distributeChart" height="200"></canvas>
    </div>

    {{-- Grafik Upper per Produk --}}
    <div class="bg-white p-6 rounded shadow border col-span-1">
      <h3 class="text-lg font-semibold mb-4 text-gray-700">📦 Total Upper per Produk Jadi</h3>
      <canvas id="chartUpperPerProduk" height="200"></canvas>
    </div>

    {{-- Grafik Distribusi per Produk --}}
    <div class="bg-white p-6 rounded shadow border col-span-1">
      <h3 class="text-lg font-semibold mb-4 text-gray-700">🚛 Total Distribusi per Produk Jadi</h3>
      <canvas id="chartDistribusiPerProduk" height="200"></canvas>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const upperData = @json($upperData);
  const distributeData = @json($distributeData);

  const upperChart = new Chart(document.getElementById('upperChart'), {
    type: 'bar',
    data: {
      labels: upperData.map(d => d.tanggal),
      datasets: [{
        label: 'Total Upper',
        data: upperData.map(d => d.total),
        backgroundColor: '#34d399',
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } }
    }
  });

  const distributeChart = new Chart(document.getElementById('distributeChart'), {
    type: 'bar',
    data: {
      labels: distributeData.map(d => d.tanggal),
      datasets: [{
        label: 'Total Distribusi',
        data: distributeData.map(d => d.total),
        backgroundColor: '#60a5fa',
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } }
    }
  });

  const upperPerProduk = @json($produkUpperData->map(fn($item) => [
    'label' => $item->produkJadi->nama_produk_jadi ?? 'Tidak Diketahui',
    'total' => $item->total
  ]));

  new Chart(document.getElementById('chartUpperPerProduk'), {
    type: 'bar',
    data: {
      labels: upperPerProduk.map(d => d.label),
      datasets: [{
        label: 'Total Upper',
        data: upperPerProduk.map(d => d.total),
        backgroundColor: '#22c55e',
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } }
    }
  });

  const distribusiPerProduk = @json($produkDistributeData);

  new Chart(document.getElementById('chartDistribusiPerProduk'), {
    type: 'bar',
    data: {
      labels: Object.keys(distribusiPerProduk),
      datasets: [{
        label: 'Total Distribusi',
        data: Object.values(distribusiPerProduk),
        backgroundColor: '#3b82f6',
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } }
    }
  });
  setTimeout(() => {
    const alert = document.querySelector('.alert-success');
    if (alert) alert.remove();
  }, 4000); // 4 detik

</script>
@endsection
