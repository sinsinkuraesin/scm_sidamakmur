@extends('admin.layout')

@section('content')

<div class="content-wrapper">
    <div class="mb-4">
        <h2 class="font-weight-bold">Selamat Datang Admin</h2>
        <p class="text-muted">Selamat beraktivitas dalam memanajemen rantai pasok <strong>PD Sidamakmur</strong></p>
    </div>

    <div id="ikan-container"></div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-4">
                <h5 class="mb-2"><b>📊 Grafik Data Konsumen</b></h5>
                <p class="text-muted mb-3">Menampilkan 10 konsumen dengan transaksi terbanyak</p>
                <canvas id="konsumenChart" style="height: 300px;"></canvas>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm p-4">
                <h5 class="mb-2"><b>🐟 Grafik Stok Ikan</b></h5>
                <p class="text-muted mb-3">Perbandingan stok ikan berdasarkan bulan</p>
                <canvas id="stokChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm p-4">
                <h5 class="mb-2"><b>💰 Grafik Pemasukan & Pengeluaran</b></h5>
                <p class="text-muted mb-3">Data keuangan penjualan dan pembelian per bulan</p>
                <canvas id="transaksiChart" style="height: 350px;"></canvas>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const konsumenCtx = document.getElementById('konsumenChart').getContext('2d');
    new Chart(konsumenCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($konsumenTransaksi->pluck('nama_konsumen')) !!},
            datasets: [{
                label: 'Jumlah Transaksi',
                data: {!! json_encode($konsumenTransaksi->pluck('jumlah')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
            }
        }
    });

    const stokCtx = document.getElementById('stokChart').getContext('2d');
    const stokData = @json($stokIkan->groupBy('jenis_ikan'));
    const bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

    const datasetsStok = Object.keys(stokData).map((jenis, i) => ({
        label: jenis,
        data: Array.from({length: 12}, (_, b) => {
            const record = stokData[jenis].find(s => s.bulan == b + 1);
            return record ? record.total : 0;
        }),
        backgroundColor: `hsl(${i * 60}, 70%, 60%)`,
        borderWidth: 1
    }));

    new Chart(stokCtx, {
        type: 'bar',
        data: { labels: bulanLabels, datasets: datasetsStok },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    const transaksiCtx = document.getElementById('transaksiChart').getContext('2d');
    const pemasukan = @json($pemasukan);
    const pengeluaran = @json($pengeluaran);
    const dataPemasukan = Array(12).fill(0);
    const dataPengeluaran = Array(12).fill(0);
    pemasukan.forEach(p => dataPemasukan[p.bulan - 1] = p.total);
    pengeluaran.forEach(p => dataPengeluaran[p.bulan - 1] = p.total);

    new Chart(transaksiCtx, {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [
                {
                    label: 'Pemasukan',
                    data: dataPemasukan,
                    borderColor: 'green',
                    backgroundColor: 'rgba(0,255,0,0.2)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Pengeluaran',
                    data: dataPengeluaran,
                    borderColor: 'red',
                    backgroundColor: 'rgba(255,0,0,0.2)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true } }
        }
    });

    const ikanContainer = document.getElementById('ikan-container');
    const ikanImages = [
        "{{ asset('images/ikan/gurame.png') }}",
        "{{ asset('images/ikan/lele.png') }}",
        "{{ asset('images/ikan/mas.png') }}",
        "{{ asset('images/ikan/mass.png') }}",
        "{{ asset('images/ikan/mujaer.png') }}"
    ];

</script>
@endsection
