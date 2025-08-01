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
                <small>(Persedian atau pencatatan stok)</small>
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

    <!-- Grafik Chart.js -->
    <div class="row">
        <!-- UPSTREAM -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-4 w-100">
                <h5 class="mb-2"><b>📦 Upstream</b></h5>
                <p class="text-muted mb-3">Jumlah pembelian dari supplier</p>
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
                <p class="text-muted mb-3">Total pemasukan penjualan per bulan</p>
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
