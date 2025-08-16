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
                <!-- Upstream -->
                <div class="scm-box text-start" tabindex="0">
                    <div class="scm-title"><em>Upstream</em></div>
                    <ul class="scm-list">
                        <li>Perencanaan kebutuhan</li>
                        <li>Pemilihan pemasok</li>
                        <li>Pengadaan bahan baku</li>
                    </ul>
                </div>

                <!-- Panah -->
                <div class="scm-arrow-wrapper">
                    <span class="scm-arrow">→</span>
                </div>

                <!-- Internal -->
                <div class="scm-box text-start" tabindex="0">
                    <div class="scm-title"><em>Internal Supply Chain</em></div>
                    <ul class="scm-list">
                        <li>Pengelolaan persediaan</li>
                        <li>Perencanaan produksi</li>
                        <li>Penyimpanan dan kontrol kualitas</li>
                    </ul>
                </div>

                <!-- Panah -->
                <div class="scm-arrow-wrapper">
                    <span class="scm-arrow">→</span>
                </div>

                <!-- Downstream -->
                <div class="scm-box text-start" tabindex="0">
                    <div class="scm-title"><em>Downstream</em></div>
                    <ul class="scm-list">
                    <li>Pengiriman</li>
                    <li>Retail (konsumen akhir)</li>
                    </ul>
                </div>
            </div>
        </div>

    <style>
    .scm-box {
        background-color: #e8f4fe;
        border: 2px solid #bcdff9;
        border-radius: 10px;
        padding: 20px;
        width: 260px;
        min-height: 200px;
        box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        transition: all 0.2s ease;
        margin: 10px;
    }
    .scm-box:active {
        transform: scale(1.03);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        border-color: #90c8f0;
    }
    .scm-title {
        font-weight: bold;
        color: #4B45C6;
        margin-bottom: 10px;
        font-size: 1.1rem;
    }
    .scm-list {
        list-style-type: disc;
        padding-left: 20px;
        margin: 0;
    }
    .scm-list li {
        text-align: justify;
        margin-bottom: 5px;
        color: #0d3d80;
        font-size: 0.95rem;
    }
    .scm-arrow-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 10px;
    }

    .scm-arrow {
        font-size: 1rem;
        color: #4B45C6;
        line-height: 1;
    }

    .scm-arrow-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
    }

    .scm-arrow {
        font-size: 1.8rem;
        color: #4B45C6;
    }

    .card-custom {
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(56, 142, 60, 0.15);
        background: #ffffff;
        padding: 28px;
        transition: 0.3s ease;
        border-left: 6px solid #4B45C6;
        height: 100%;
    }

    .card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(46, 125, 50, 0.2);
    }

    .card-custom h5 {
        font-weight: 600;
        font-size: 1.2rem;
        color: #4B45C6;
        margin-bottom: 8px;
    }

    @media (max-width: 200px) {
        .d-flex.flex-wrap .scm-arrow {
            display: none;
        }
        }
    .text-purple {
    color: #4B45C6; /* Ungu pastel seperti di grafik downstream */
}

    </style>

   <!-- GRAFIK -->
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

// === UPSTREAM ===
const supplierMap = {!! json_encode($supplierMapPerTanggal) !!};

const upstreamChart = new Chart(document.getElementById('upstreamChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($tanggalLabels) !!},
        datasets: {!! json_encode($datasets) !!}
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





// === INTERNAL ===
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


// === DOWNSTREAM ===
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
