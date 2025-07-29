@extends('pemilik.layout')
@section('content')

<!-- Tambah Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body {
        background: linear-gradient(to right, #e3f2fd, #e8f5e9);
        font-family: 'Poppins', sans-serif;
    }

    .header-gradient {
        background: linear-gradient(135deg, #43a047, #66bb6a);
        padding: 45px 30px;
        border-radius: 20px;
        color: #ffffff;
        text-align: center;
        margin-bottom: 35px;
        box-shadow: 0 4px 24px rgba(76, 175, 80, 0.4);
    }

    .header-gradient h1 {
        font-size: 2.6rem;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .header-gradient p {
        font-size: 1.2rem;
        font-weight: 400;
        color: #e8f5e9;
        max-width: 800px;
        margin: 0 auto;
    }

    .scm-box {
        background-color: #e8f5e9;
        border: 2px solid #a5d6a7;
        border-radius: 10px;
        padding: 15px 20px;
        width: 220px;
        margin: 10px;
        text-align: center;
        font-weight: bold;
        color: #2e7d32;
        box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
    }

    .scm-arrow {
        font-size: 2rem;
        margin: 0 10px;
        color: #388e3c;
    }

    .scm-box em {
        font-style: italic;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .card-custom {
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(56, 142, 60, 0.15);
        background: #ffffff;
        padding: 28px;
        transition: 0.3s ease;
        border-left: 6px solid #81c784;
        height: 100%;
    }

    .card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(46, 125, 50, 0.2);
    }

    .card-custom h6, .card-custom h5 {
        font-weight: 600;
        font-size: 1.2rem;
        color: #388e3c;
        margin-bottom: 8px;
    }

    .text-muted {
        color: #757575 !important;
    }

    canvas {
        background: #f5fff5;
        border-radius: 12px;
        padding: 12px;
    }

    @media (max-width: 768px) {
        .header-gradient h1 {
            font-size: 2rem;
        }

        .header-gradient p {
            font-size: 1rem;
        }

        .d-flex.flex-wrap .scm-arrow {
            display: none;
        }
    }
</style>

<div class="container-fluid py-4">

   <!-- Header -->
<div class="py-5 text-center text-green" >
    <h1 class="display-5 fw-bold mb-2">👋 Selamat Datang, Pemilik!</h1>
    <p class="lead mb-0">Kelola rantai pasok <strong>PD Sidamakmur</strong> dengan lebih mudah dan efisien.</p>
</div>

<!-- Gambar Siklus SCM -->
<div class="my-5 text-center">
    <h4 class="fw-bold mb-4">🔄 Siklus Supply Chain Management</h4>
    <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
        <!-- Upstream -->
        <div class="p-4 shadow-sm rounded-3 bg-light text-dark text-center" style="min-width: 200px;">
            <div class="fw-semibold text-success mb-1">Upstream</div>
            <small class="text-muted fst-italic">(Pengadaan dari berbagai supplier)</small>
        </div>

        <div class="mx-2 display-6 text-muted">→</div>

        <!-- Internal Supply Chain -->
        <div class="p-4 shadow-sm rounded-3 bg-light text-dark text-center" style="min-width: 200px;">
            <div class="fw-semibold text-primary mb-1">Internal Supply Chain</div>
            <small class="text-muted fst-italic">(Penyimpanan dan pencatatan stok)</small>
        </div>

        <div class="mx-2 display-6 text-muted">→</div>

        <!-- Downstream -->
        <div class="p-4 shadow-sm rounded-3 bg-light text-dark text-center" style="min-width: 200px;">
            <div class="fw-semibold text-danger mb-1">Downstream</div>
            <small class="text-muted fst-italic">(Distribusi dan penjualan kepada konsumen)</small>
        </div>
    </div>
</div>


    <!-- Grafik Baris 1 -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card-custom p-3">
            <h5>📦 Upstream</h5>
            <p class="text-muted mb-2">Jumlah pembelian dari supplier</p>
            <canvas id="upstreamChart" style="height: 200px;"></canvas>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card-custom p-3">
            <h5>🏪 Internal Supply Chain</h5>
            <p class="text-muted mb-2">Stok ikan per bulan</p>
            <canvas id="internalChart" style="height: 200px;"></canvas>
        </div>
    </div>
</div>

<!-- Grafik Baris 2 -->
<div class="row justify-content-center">
    <div class="col-md-6 mb-4">
        <div class="card-custom p-3">
            <h5>🛒 Downstream</h5>
            <p class="text-muted mb-2">Total pemasukan penjualan per bulan</p>
            <canvas id="downstreamChart" style="height: 200px;"></canvas>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    const baseOptions = {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: '#2e7d32' },
                grid: { color: '#c8e6c9' }
            },
            x: {
                ticks: { color: '#2e7d32' },
                grid: { display: false }
            }
        }
    };

    new Chart(document.getElementById('upstreamChart'), {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Total Pembelian',
                data: [0, 0, 0, 120, 150, 180, 140, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        },
        options: baseOptions
    });

    new Chart(document.getElementById('internalChart'), {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [
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
            ]
        },
        options: baseOptions
    });

    new Chart(document.getElementById('downstreamChart'), {
        type: 'bar',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Total Pemasukan',
                data: [0, 0, 0, 2000000, 2500000, 2300000, 2700000, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(103, 58, 183, 0.6)',
                borderColor: 'rgba(103, 58, 183, 1)',
                borderWidth: 1
            }]
        },
        options: {
            ...baseOptions,
            scales: {
                ...baseOptions.scales,
                y: {
                    ...baseOptions.scales.y,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                ...baseOptions.plugins,
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
