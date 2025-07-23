<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 30px;
            color: #333;
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

        // Judul laporan berdasarkan filter
        $judul = 'Laporan Penjualan';
        if ($filter === 'bulan') {
            $judul .= ' - Bulan ' . Carbon::parse($tanggal)->translatedFormat('F');
        } elseif ($filter === 'tahun') {
            $judul .= ' - Tahun ' . Carbon::parse($tanggal)->translatedFormat('Y');
        } else {
            $judul .= ' - Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');
        }

        // Penyesuaian path logo
        $isPdf = request()->routeIs('laporan.penjualan.pdf');
        $logoPath = $isPdf
            ? 'file://' . public_path('images/logo.png')
            : asset('images/logo.png');
    @endphp

    <div class="laporan-header">
       <img src="file://{{ public_path('images/logo.png') }}" alt="Logo">

    </div>

    <h1>{{ $judul }}</h1>
    <hr>

    <div class="section-title">Detail Penjualan</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Konsumen</th>
                <th>Jenis Ikan</th>
                <th>Jumlah Ikan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penjualans as $jual)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($jual->tgl_jual)->format('d-m-Y') }}</td>
                    <td>{{ $jual->konsumen->nama_konsumen ?? '-' }}</td>
                    <td>
                        <ul style="margin:0; padding-left: 15px;">
                            @foreach($jual->detailJual as $detail)
                                <li>{{ $detail->ikan->jenis_ikan ?? '-' }} - {{ $detail->jml_ikan }} Kg</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $jual->detailJual->sum('jml_ikan') }} Kg</td>
                    <td>Rp {{ number_format($jual->detailJual->sum('total'), 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" style="text-align:right;">Total Penjualan</td>
                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="5" style="text-align:right;">Total Ikan Terjual</td>
                <td>{{ number_format($total_kg, 0, ',', '.') }} Kg</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
    <p>Hormat Kami,</p>
    <img src="file://{{ public_path('images/ttd.jpg') }}" style="height: 80px;" alt="Tanda Tangan">
    <p><strong>PD Sidamakmur</strong></p>
</div>


</body>
</html>
