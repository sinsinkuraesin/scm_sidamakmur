@extends('pemilik.layout')
@section('content')

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
        width: 240px;
        margin: 10px;
        text-align: left;
        font-weight: bold;
        color: #2e7d32;
        box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
    }

    .scm-title {
        font-size: 1.1rem;
        color: #2e7d32;
        margin-bottom: 8px;
    }

    .scm-list {
        list-style-type: disc;
        padding-left: 20px;
        margin: 0;
    }

    .scm-list li {
        text-align: left;
        font-size: 0.95rem;
        color: #0d3d80;
        margin-bottom: 4px;
    }

    .scm-arrow-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
    }

    .scm-arrow {
        font-size: 1.8rem;
        color: #2e7d32;
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

    .card-custom h5 {
        font-weight: 600;
        font-size: 1.2rem;
        color: #388e3c;
        margin-bottom: 8px;
    }

    canvas {
        background: #f5fff5;
        border-radius: 12px;
        padding: 12px;
    }

    @media (max-width: 768px) {
        .scm-arrow-wrapper {
            display: none;
        }
    }
</style>

<div class="container-fluid py-4">

    <!-- Header -->
    <div class="py-5 text-center text-green">
        <h1 class="display-5 fw-bold mb-2">👋 Selamat Datang, Pemilik!</h1>
        <p class="lead mb-0">Kelola rantai pasok <strong>PD Sidamakmur</strong> dengan lebih mudah dan efisien.</p>
    </div>

    <!-- Siklus SCM -->
    <div class="mb-5 text-center">
        <h4 class="font-weight-bold mb-4">🔄 Siklus Supply Chain Management</h4>
        <div class="d-flex justify-content-center align-items-stretch flex-wrap">
            <!-- Upstream -->
            <div class="scm-box text-start">
                <div class="scm-title"><em>Upstream</em></div>
                <ul class="scm-list">
                    <li>Perencanaan kebutuhan</li>
                    <li>Pemilihan pemasok</li>
                    <li>Pengadaan bahan baku</li>
                </ul>
            </div>

            <div class="scm-arrow-wrapper">
                <span class="scm-arrow">➔</span>
            </div>

            <!-- Internal -->
            <div class="scm-box text-start">
                <div class="scm-title"><em>Internal Supply Chain</em></div>
                <ul class="scm-list">
                    <li>Pengelolaan dan kontrol persediaan</li>
                    <li>Perencanaan produksi</li>
                </ul>
            </div>

            <div class="scm-arrow-wrapper">
                <span class="scm-arrow">➔</span>
            </div>

            <!-- Downstream -->
            <div class="scm-box text-start">
                <div class="scm-title"><em>Downstream</em></div>
                <ul class="scm-list">
                    <li>Pengiriman</li>
                    <li>Retail (konsumen akhir)</li>
                </ul>
            </div>
        </div>
    </div>

<div class="row">
        <!-- Upstream Chart -->
        <div class="col-md-6 mb-4">
            <div class="card-custom">
                <h5>📦 1. Grafik Pengadaan Bahan Baku</h5>
                <p class="text-muted">Jumlah pembelian ikan per jenis setiap hari dari pemasok.</p>
                <canvas id="upstreamChart" style="height: 200px;"></canvas>
            </div>
        </div>

        <!-- Internal Chart -->
        <div class="col-md-6 mb-4">
            <div class="card-custom">
                <h5>🏪 2. Grafik Stok Persediaan</h5>
                <p class="text-muted">Stok ikan per hari (1 minggu terakhir)</p>
                <canvas id="internalChart" style="height: 200px;"></canvas>
            </div>
        </div>

        <!-- Downstream Chart -->
        <div class="col-md-12 mb-4">
        <div class="card-custom">
            <h5>🐟 Grafik Distribusi Jenis Ikan ke Pasar</h5>
            <p class="text-muted">Total penjualan ikan per jenis per hari. Klik/hover untuk melihat distribusi ke pasar.</p>
            <canvas id="downstreamChart" style="height: 250px;"></canvas>
        </div>
    </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function formatRupiah(value) {
    return 'Rp ' + Number(value).toLocaleString('id-ID');
}

// === Grafik 1 ===
const supplierMap = {!! json_encode($supplierMapPerTanggal) !!};

const upstreamChart = new Chart(document.getElementById('upstreamChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($tanggalLabels) !!},
        datasets: {!! json_encode($upstreamDatasets) !!}   // pakai upstreamDatasets
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom', // seperti Grafik 2
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const tanggalKey = {!! json_encode($tanggalKeys) !!}[context.dataIndex];
                        const jenisIkan = context.dataset.label;
                        const suppliers = supplierMap[tanggalKey][jenisIkan] || [];
                        return `${jenisIkan}: ${context.raw} Kg (Supplier: ${suppliers.join(', ')})`;
                    }
                }
            }
        },
        scales: {
            x: { stacked: false },
            y: { beginAtZero: true, title: { display: true, text: 'Jumlah (Kg)' } }
        }
    }
});





// === Grafik 2 ===
const stokColors = [
    '#42a5f5', '#66bb6a', '#ffa726', '#ab47bc', '#26c6da',
    '#ff7043', '#8d6e63', '#26a69a', '#ec407a', '#7e57c2'
];

new Chart(document.getElementById('internalChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($tanggalLabels) !!},
        datasets: [
            @foreach ($stokChartData as $index => $data)
            {
                label: '{{ $data['label'] }}',
                data: {!! json_encode($data['data']) !!}, // sisa stok harian
                backgroundColor: stokColors[{{ $index }} % stokColors.length],
                borderWidth: 1
            },
            @endforeach
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: { stacked: true },
            y: {
                stacked: true,
                beginAtZero: true,
                title: { display: true, text: 'Sisa Stok (Kg)' }
            }
        },
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.raw} Kg` } }
        }
    }
});


// === grafik3 ===
const ikanPasarMap = {!! json_encode($ikanPasarMap) !!};

new Chart(document.getElementById('downstreamChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($tanggalLabels) !!},
        datasets: {!! json_encode($datasets) !!}
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const tanggalKey = {!! json_encode($tanggalKeys) !!}[context.dataIndex];
                        const jenisIkan = context.dataset.label;
                        const pasarDistribusi = ikanPasarMap[tanggalKey]?.[jenisIkan] || {};

                        let label = `${jenisIkan}: ${context.raw} Kg`;
                        let detailPasar = Object.entries(pasarDistribusi)
                            .map(([pasar, kg]) => `→ ${pasar}: ${kg} Kg`)
                            .join('\n');

                        return label + '\n' + detailPasar;
                    }
                }
            }
        },
        scales: {
            x: { stacked: false },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Jumlah (Kg)'
                }
            }
        }
    }
});


</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil Login',
    text: '{{ session('success') }}',
    showConfirmButton: false,
    timer: 2000
});
</script>
@endif
@endsection

