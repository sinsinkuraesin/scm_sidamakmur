<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
@php
    use Carbon\Carbon;

    Carbon::setLocale('id'); // pastikan Carbon pakai lokal Indonesia

    $judul = 'Laporan Pembelian';

    if ($filter === 'bulanan') {
        $bulan = Carbon::parse($tanggal)->translatedFormat('F');
        $judul .= ' - Bulan ' . $bulan;
    } elseif ($filter === 'tahunan') {
        $tahun = Carbon::parse($tanggal)->translatedFormat('Y');
        $judul .= ' - Tahun ' . $tahun;
    } else {
        $tgl = Carbon::parse($tanggal)->translatedFormat('d F Y');
        $judul .= ' - Tanggal ' . $tgl;
    }
@endphp

<h2>{{ $judul }}</h2>

<table>
    <thead>
        <tr>
            <th>Nomor</th>
            <th>Tanggal</th>
            <th>Kode Supplier</th>
            <th>Jenis Ikan</th>
            <th>Jumlah</th>
            <th>Harga/Kg</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($belis as $beli)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ \Carbon\Carbon::parse($beli->tgl_beli)->translatedFormat('d-m-Y') }}</td>
            <td>{{ $beli->supplier->kd_supplier ?? '-' }}</td>
            <td>{{ $beli->ikan->jenis_ikan ?? '-' }}</td>
            <td>{{ $beli->jml_ikan }} Kg</td>
            <td>Rp {{ number_format($beli->harga_beli, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($beli->total_harga, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6"><strong>Total Pengeluaran</strong></td>
            <td><strong>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</strong></td>
        </tr>
    </tfoot>
</table>

</body>
</html>
