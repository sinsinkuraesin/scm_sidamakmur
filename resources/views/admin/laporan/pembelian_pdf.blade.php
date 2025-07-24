<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 30px;
            color: #333;
            font-size: 14px;
        }

        .laporan-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .laporan-header img {
            height: 60px;
        }

        h1 {
            font-size: 22px;
            margin-top: 20px;
            text-align: center;
        }

        hr {
            border: none;
            height: 2px;
            background-color: #3f51b5;
            margin: 20px 0;
        }

        .section-title {
            font-weight: bold;
            color: #3f51b5;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        th {
            background-color: #3f51b5;
            color: white;
            padding: 8px;
        }

        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        .total-row {
            font-weight: bold;
        }

        .signature {
            text-align: right;
            margin-top: 40px;
        }

        .signature img {
            height: 80px;
            margin-bottom: 5px;
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

        $isPdf = request()->routeIs('laporan.pembelian.pdf');
        $logoPath = $isPdf
            ? 'file://' . public_path('images/logo.png')
            : asset('images/logo.png');
        $ttdPath = $isPdf
            ? 'file://' . public_path('images/ttd.jpg')
            : asset('images/ttd.jpg');
    @endphp

    <div class="laporan-header">
        <img src="{{ $logoPath }}" alt="Logo">
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
                    <td colspan="7" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" style="text-align:right;">Total Pengeluaran</td>
                <td>Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <p>Hormat Kami,</p>
        <img src="{{ $ttdPath }}" alt="Tanda Tangan">
        <p><strong>PD Sidamakmur</strong></p>
    </div>
</body>
</html>
