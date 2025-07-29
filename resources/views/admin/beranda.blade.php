@extends('admin.layout')

@section('content')

<div class="content-wrapper">
    <div class="mb-4">
        <h2 class="font-weight-bold">Selamat Datang Admin</h2>
        <p class="text-muted">Selamat beraktivitas dalam memanajemen rantai pasok <strong>PD Sidamakmur</strong></p>
    </div>

    <!-- Gambar Siklus SCM -->
    <div class="mb-5 text-center">
        <h4 class="font-weight-bold mb-4">Siklus Supply Chain Management</h4>
        <div class="d-flex justify-content-center align-items-center flex-wrap">
            <div class="scm-box">
                <div><em>Upstream</em></div>
                <small>(pengadaan dari berbagai supplier)</small>
            </div>
            <div class="scm-arrow">→</div>
            <div class="scm-box">
                <div><em>Internal supply chain</em></div>
                <small>(penyimpanan dan pencatatan stok)</small>
            </div>
            <div class="scm-arrow">→</div>
            <div class="scm-box">
                <div><em>Downstream</em></div>
                <small>(distribusi dan penjualan kepada konsumen)</small>
            </div>
        </div>
    </div>
<style>
    .scm-box {
        background-color: #e3f2fd;
        border: 2px solid #90caf9;
        border-radius: 10px;
        padding: 15px 20px;
        width: 220px;
        margin: 10px;
        text-align: center;
        font-weight: bold;
        color: #0d47a1;
        box-shadow: 2px 2px 8px rgba(0,0,0,0.1);
    }

    .scm-arrow {
        font-size: 2rem;
        margin: 0 10px;
        color: #1976d2;
    }

    .scm-box em {
        font-style: italic;
        font-weight: bold;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .d-flex.flex-wrap .scm-arrow {
            display: none;
        }
    }
</style>



    <div id="ikan-container"></div>


    <!-- BARIS 1: Upstream & Internal -->
    <div class="row">
        <!-- Grafik Upstream -->
        <div class="col-md-6 mb-4 d-flex justify-content-center">
            <div class="card shadow-sm p-4 w-100">
                <h5 class="mb-2"><b>📦 Upstream</b></h5>
                <p class="text-muted mb-3">Jumlah pembelian dari supplier</p>
                <canvas id="upstreamChart" style="height: 200px !important;"></canvas>
            </div>
        </div>

        <!-- Grafik Internal Supply Chain -->
        <div class="col-md-6 mb-4 d-flex justify-content-center">
            <div class="card shadow-sm p-4 w-100">
                <h5 class="mb-2"><b>🏪 Internal Supply Chain</b></h5>
                <p class="text-muted mb-3">Stok ikan per bulan</p>
                <canvas id="internalChart" style="height: 200px !important;"></canvas>
            </div>
        </div>
    </div>

    <!-- BARIS 2: Downstream -->
    <div class="row justify-content-center">
        <!-- Grafik Downstream -->
        <div class="col-md-6 mb-4 d-flex justify-content-center">
            <div class="card shadow-sm p-4 w-100" >
                <h5 class="mb-2"><b>🛒 Downstream</b></h5>
                <p class="text-muted mb-3">Total pemasukan penjualan per bulan</p>
                <canvas id="downstreamChart" style="height: 200px !important;"></canvas>
            </div>
        </div>
    </div>



</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

// UPSTREAM: Dummy Pembelian dari Supplier (April–Juli)
const upstreamCtx = document.getElementById('upstreamChart').getContext('2d');
const pembelianData = [0, 0, 0, 120, 150, 180, 140, 0, 0, 0, 0, 0];

new Chart(upstreamCtx, {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: 'Total Pembelian',
            data: pembelianData,
            backgroundColor: 'rgba(255, 159, 64, 0.6)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { position: 'bottom' } }
    }
});

// INTERNAL: Dummy Stok Ikan (April–Juli)
const internalCtx = document.getElementById('internalChart').getContext('2d');
const datasetsStok = [
    {
        label: 'Lele',
        data: [0, 0, 0, 60, 90, 75, 85, 0, 0, 0, 0, 0],
        backgroundColor: 'rgba(33, 150, 243, 0.7)',
        borderWidth: 1
    },
    {
        label: 'Gurame',
        data: [0, 0, 0, 50, 70, 60, 65, 0, 0, 0, 0, 0],
        backgroundColor: 'rgba(76, 175, 80, 0.7)',
        borderWidth: 1
    },
    {
        label: 'Mas',
        data: [0, 0, 0, 40, 55, 50, 45, 0, 0, 0, 0, 0],
        backgroundColor: 'rgba(255, 193, 7, 0.7)',
        borderWidth: 1
    }
];

new Chart(internalCtx, {
    type: 'bar',
    data: { labels: bulanLabels, datasets: datasetsStok },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
    }
});

// DOWNSTREAM: Dummy Pemasukan Konsumen (April–Juli)
const downstreamCtx = document.getElementById('downstreamChart').getContext('2d');
const pemasukanPerBulan = [0, 0, 0, 2000000, 2500000, 2300000, 2700000, 0, 0, 0, 0, 0];

new Chart(downstreamCtx, {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: 'Total Pemasukan',
            data: pemasukanPerBulan,
            backgroundColor: 'rgba(103, 58, 183, 0.6)',
            borderColor: 'rgba(103, 58, 183, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let val = context.raw || 0;
                        return 'Rp ' + val.toLocaleString();
                    }
                }
            }
        }
    }
});

</script>
@endsection
