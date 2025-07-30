<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok</title>
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
            font-size: 13px;
        }

        th {
            background-color: #3f51b5;
            color: white;
            padding: 8px;
            text-align: left;
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
            margin-top: 50px;
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

        $judul = 'Laporan Stok Persediaan Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');

        $isPdf = request()->routeIs('laporan.persediaan.pdf');
        $logoPath = $isPdf ? 'file://' . public_path('images/logo.png') : asset('images/logo.png');
        $ttdPath = $isPdf ? 'file://' . public_path('images/ttd.jpg') : asset('images/ttd.jpg');
    @endphp

    <div class="laporan-header">
        <img src="{{ $logoPath }}" alt="Logo PD Sidamakmur">
    </div>

    <h1>{{ $judul }}</h1>
    <hr>

    <div class="section-title">Rekapitulasi Stok</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis Ikan</th>
                <th>Stok Awal (Kg)</th>
                <th>Stok Masuk (Kg)</th>
                <th>Stok Keluar (Kg)</th>
                <th>Stok Akhir (Kg)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalMasuk = 0;
                $totalKeluar = 0;
                $totalAkhir = 0;
            @endphp

            @forelse($laporanStok as $i => $item)
                @php
                    $totalMasuk += $item['masuk'];
                    $totalKeluar += $item['keluar'];
                    $totalAkhir += $item['stok_akhir'];
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                    <td>{{ $item['jenis_ikan'] }}</td>
                    <td>{{ number_format($item['stok_awal']) }}Kg</td>
                    <td>{{ number_format($item['masuk'] ) }}Kg</td>
                    <td>{{ number_format($item['keluar'] ) }}Kg</td>
                    <td>{{ number_format($item['stok_akhir'] ) }}Kg</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data tersedia</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total</td>
                <td>{{ number_format($totalMasuk) }}Kg</td>
                <td>{{ number_format($totalKeluar) }}Kg</td>
                <td>{{ number_format($totalAkhir) }}Kg</td>
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
