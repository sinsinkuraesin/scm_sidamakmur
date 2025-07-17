@extends('pemilik.layout')

@section('content')
<div class="content-wrapper">

  {{-- Header sambutan --}}
  <div class="row">
    <div class="col-md-12 grid-margin">
      <div class="card shadow-sm border-0 bg-light">
        <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
          <div>
            <h3 class="font-weight-bold mb-2">
              <i class="mdi mdi-hand-wave text-warning mr-2"></i>
              Selamat Datang, <span class="text-primary">Pemilik</span>!
            </h3>
            <h6 class="font-weight-normal text-muted">
              Semoga harimu produktif dalam mengelola rantai pasok <span class="font-weight-bold text-primary">PD Sidamakmur</span>.
            </h6>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Kartu Jumlah Data --}}
  <div class="row">
    {{-- Data Ikan --}}
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card text-white card-hover" style="background: linear-gradient(135deg, #007bff, #0056b3);">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title text-white">Jumlah Data Ikan</h5>
            <h3 class="font-weight-bold mb-0">{{ $jumlahIkan ?? 4 }}</h3>
          </div>
          <i class="mdi mdi-fish mdi-48px"></i>
        </div>
      </div>
    </div>

    {{-- Data Supplier --}}
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card text-white card-hover" style="background: linear-gradient(135deg, #28a745, #1e7e34);">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title text-white">Jumlah Data Supplier</h5>
            <h3 class="font-weight-bold mb-0">{{ $jumlahSupplier ?? 35 }}</h3>
          </div>
          <i class="mdi mdi-truck mdi-48px"></i>
        </div>
      </div>
    </div>

    {{-- Data Konsumen --}}
    <div class="col-md-4 grid-margin stretch-card">
      <div class="card text-white card-hover" style="background: linear-gradient(135deg, #17a2b8, #117a8b);">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title text-white">Jumlah Data Konsumen</h5>
            <h3 class="font-weight-bold mb-0">{{ $jumlahKonsumen ?? 12 }}</h3>
          </div>
          <i class="mdi mdi-account-group mdi-48px"></i>
        </div>
      </div>
    </div>
  </div>

  {{-- Grafik --}}
  <div class="row">
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <p class="card-title">Grafik Penjualan</p>
          <p class="font-weight-500">Grafik ini menyajikan data stok ikan yang tersedia secara real time</p>
          <div class="d-flex flex-wrap mb-5"></div>
          <canvas id="order-chart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <p class="card-title">Grafik Pembelian</p>
          <p class="font-weight-500">Grafik ini menyajikan data konsumen yang ada pada PD Sidamakmur</p>
          <div id="sales-legend" class="chartjs-legend mt-4 mb-2"></div>
          <canvas id="sales-chart"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Optional CSS Animasi Hover --}}
<style>
  .card-hover {
    transition: transform 0.2s ease-in-out;
  }
  .card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
  }
</style>
@endsection
