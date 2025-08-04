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
        color: #0d6efd;
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
        color: #0d6efd;
        line-height: 1;
    }

    @media (max-width: 200px) {
        .d-flex.flex-wrap .scm-arrow {
            display: none;
        }
        }
    </style>

    <!-- Grafik Chart.js -->
    <div class="row">
        <!-- UPSTREAM -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-4 w-100">
                <h5 class="mb-2"><b>📦 Upstream</b></h5>
               <p class="text-muted mb-3">Total pengeluaran berdasarkan jumlah pembelian ikan ke supplier /bulan</p>
                <canvas id="upstreamChart" style="height: 200px !important;"></canvas>
            </div>
        </div>

        <!-- INTERNAL -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-4">
                <h5 class="mb-2"><b>🏪 Internal Supply Chain</b></h5>
                <p class="text-muted mb-3">Stok ikan per hari (1 minggu terakhir)</p>
                <canvas id="internalChart" style="height: 200px !important;"></canvas>
            </div>
        </div>

        <!-- DOWNSTREAM -->
        <div class="col-md-6 mb-4 mx-auto">
            <div class="card shadow-sm p-4 w-100">
                <h5 class="mb-2"><b>🛒 Downstream</b></h5>
                <p class="text-muted mb-3">Total pemasukan berdasarkan jumlah penjualan kepada konsumen /bulan</p>
                <canvas id="downstreamChart" style="height: 200px !important;"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

// FORMAT RUPIAH
function formatRupiah(value) {
    return 'Rp ' + Number(value).toLocaleString('id-ID');
}

// UPSTREAM (Pembelian)
new Chart(document.getElementById('upstreamChart'), {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: 'Total Pembelian',
            data: {!! json_encode(array_column($pembelian, 'total')) !!},
            backgroundColor: 'rgba(255, 159, 64, 0.6)',
            borderColor: 'rgba(255, 159, 64, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: formatRupiah
                }
            }
        },
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => formatRupiah(ctx.raw)
                }
            }
        }
    }
});

// INTERNAL (Stok per minggu)

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
                data: {!! json_encode($data['data']) !!},
                backgroundColor: stokColors[{{ $index }} % stokColors.length],
                borderWidth: 1
            },
            @endforeach
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                stacked: true,
                ticks: {
                    autoSkip: false,
                    maxRotation: 0,
                    minRotation: 0
                }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Jumlah Stok'
                }
            }
        },
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => `${ctx.dataset.label}: ${ctx.raw} Kg`
                }
            }
        }
    }
});


// DOWNSTREAM (Pemasukan)
new Chart(document.getElementById('downstreamChart'), {
    type: 'bar',
    data: {
        labels: bulanLabels,
        datasets: [{
            label: 'Total Pemasukan',
            data: {!! json_encode(array_column($konsumenTransaksi, 'total')) !!},
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
                    callback: formatRupiah
                }
            }
        },
        plugins: {
            legend: { position: 'bottom' },
            tooltip: {
                callbacks: {
                    label: ctx => formatRupiah(ctx.raw)
                }
            }
        }
    }
});
</script>
@endsection
