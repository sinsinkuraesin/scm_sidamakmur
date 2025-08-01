<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            margin: 40px;
        }

        .laporan-header {
            text-align: left;
            margin-bottom: 20px;
        }

        .laporan-header img {
            width: 100px;
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .section-title {
            font-weight: bold;
            color: #2c3e50;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #333;
        }

        th {
            background-color: #3f51b5;
            color: white;
            padding: 8px;
            text-align: center;
        }

        td {
            padding: 8px;
            text-align: center;
        }

        .total-row {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .signature {
            margin-top: 80px;
            text-align: right;
        }

        .signature img {
            width: 120px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    @php
        use Carbon\Carbon;
        Carbon::setLocale('id');

        $judul = 'Laporan Pembelian';
        if ($filter === 'bulanan') {
            $judul .= ' - Bulan ' . Carbon::parse($tanggal)->translatedFormat('F');
        } elseif ($filter === 'tahunan') {
            $judul .= ' - Tahun ' . Carbon::parse($tanggal)->translatedFormat('Y');
        } else {
            $judul .= ' - Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');
        }

        $logoPath = public_path('images/logo.png');
        $ttdPath = public_path('images/ttd.jpg');
    @endphp

    <div class="laporan-header">
        <img src="file://{{ $logoPath }}" alt="Logo">
    </div>

    <h1>{{ $judul }}</h1>
    <hr>

    <div class="section-title">Detail Pembelian</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode Supplier</th>
                <th>Jenis Ikan</th>
                <th>Jumlah</th>
                <th>Harga/Kg</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($belis as $beli)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($beli->tgl_beli)->translatedFormat('d-m-Y') }}</td>
                    <td>{{ $beli->supplier->kd_supplier ?? '-' }}</td>
                    <td>{{ $beli->ikan->jenis_ikan ?? '-' }}</td>
                    <td>{{ $beli->jml_ikan }} Kg</td>
                    <td>Rp {{ number_format($beli->harga_beli, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($beli->total_harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">Total Pengeluaran</td>
                <td>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <p>Hormat Kami,</p>
        <img src="file://{{ $ttdPath }}" alt="Tanda Tangan">
        <p><strong>PD Sidamakmur</strong></p>
    </div>
</body>
</html>
