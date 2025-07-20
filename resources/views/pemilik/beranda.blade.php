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

    .card-custom h6 {
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
    }
</style>

<div class="container-fluid py-4">

    <!-- Header -->
    <div class="header-gradient">
        <h1>👋 Selamat Datang, Pemilik!</h1>
        <p>Dashboard ini dirancang khusus untuk memudahkan Anda dalam memantau dan mengembangkan bisnis <strong>PD Sidamakmur</strong>. Semangat terus dan pantau datamu setiap hari! 📊</p>
    </div>

    <!-- Baris Grafik 1 -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card-custom">
                <h6>📦 Grafik Stok Ikan</h6>
                <p class="text-muted">Data stok per bulan dari Januari hingga Juli</p>
                <canvas id="grafikStok"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card-custom">
                <h6>👥 Konsumen Teraktif</h6>
                <p class="text-muted">10 Konsumen dengan jumlah transaksi terbanyak</p>
                <canvas id="grafikKonsumen"></canvas>
            </div>
        </div>
    </div>

    <!-- Baris Grafik 2 -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card-custom">
                <h6>💰 Penjualan Bulanan</h6>
                <p class="text-muted">Total penjualan per bulan (Rp Juta)</p>
                <canvas id="grafikPenjualan"></canvas>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card-custom">
                <h6>🚚 Pemasok Aktif</h6>
                <p class="text-muted">Jumlah suplai dari pemasok utama</p>
                <canvas id="grafikPemasok"></canvas>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const baseOptions = {
        responsive: true,
        plugins: {
            legend: { display: false }
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

    new Chart(document.getElementById('grafikStok'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
            datasets: [{
                label: 'Stok (kg)',
                data: [80, 90, 70, 100, 85, 95, 88],
                backgroundColor: '#a5d6a7',
                borderColor: '#388e3c',
                borderWidth: 1
            }]
        },
        options: baseOptions
    });

    new Chart(document.getElementById('grafikKonsumen'), {
        type: 'bar',
        data: {
            labels: ['Ali', 'Budi', 'Citra', 'Dina', 'Eko', 'Fajar', 'Gina', 'Hadi', 'Ina', 'Joko'],
            datasets: [{
                label: 'Jumlah Transaksi',
                data: [5, 7, 3, 9, 6, 4, 8, 2, 7, 5],
                backgroundColor: '#81c784',
                borderColor: '#388e3c',
                borderWidth: 1
            }]
        },
        options: baseOptions
    });

    new Chart(document.getElementById('grafikPenjualan'), {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
            datasets: [{
                label: 'Penjualan (Rp Juta)',
                data: [12, 15, 10, 18, 14, 20, 16],
                backgroundColor: '#c5e1a5',
                borderColor: '#689f38',
                borderWidth: 1
            }]
        },
        options: baseOptions
    });

    new Chart(document.getElementById('grafikPemasok'), {
        type: 'bar',
        data: {
            labels: ['PT Mina', 'CV Lautan', 'UD Samudra', 'TPI Indah', 'Koperasi Segar'],
            datasets: [{
                label: 'Jumlah Suplai',
                data: [20, 18, 15, 22, 17],
                backgroundColor: '#aed581',
                borderColor: '#558b2f',
                borderWidth: 1
            }]
        },
        options: baseOptions
    });
</script>

@endsection
