<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            background-color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .invoice-box {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            max-width: 800px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            position: relative;
        }

        .logo-kanan {
            position: absolute;
            top: 30px;
            right: 30px;
        }

        .logo-kanan img {
            height: 60px;
        }

        .header-tengah {
            text-align: center;
            margin-bottom: 5px;
        }

        .header-tengah h2 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }

        .header-tengah p {
            margin: 2px 0;
            font-size: 14px;
        }

        .line {
            border-top: 2px solid #000;
            margin: 20px 0;
        }

        .invoice-header {
            text-align: center;
        }

        .section-title {
            font-weight: bold;
            color: #3f51b5;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #3f51b5;
            color: white;
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

        .footer-note {
            text-align: center;
            color: #888;
            font-size: 12px;
            margin-top: 30px;
        }
    </style>
</head>
<body>
@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
    $judul = 'Laporan Penjualan';
    if ($filter === 'bulan') {
        $judul .= ' - Bulan ' . Carbon::parse($tanggal)->translatedFormat('F');
    } elseif ($filter === 'tahun') {
        $judul .= ' - Tahun ' . Carbon::parse($tanggal)->translatedFormat('Y');
    } else {
        $judul .= ' - Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');
    }
    $isPdf = request()->routeIs('laporan.penjualan.pdf');
    $logoPath = $isPdf ? public_path('images/logo.png') : asset('images/logo.png');
@endphp

<div class="invoice-box">
    <div class="invoice-header-top">
        <div class="header-tengah">
            <h2>PD. SIDAMAKMUR</h2>
            <p>Blok. Kadutilu, Dukupuntang Cirebon - 45652</p>
            <p>Telp. 085317889229</p>
        </div>
        <div class="logo-kanan">
            <img src="file://{{ public_path('images/logo.png') }}" alt="Logo">
        </div>
        <div class="line"></div>
    </div>

    <div class="invoice-header">
        <h4 class="text-primary fw-bold">{{ $judul }}</h4>
    </div>

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
        <img src="file://{{ public_path('images/ttd.jpg') }}" alt="Tanda Tangan">
        <p><strong>PD Sidamakmur</strong></p>
    </div>

    <p class="footer-note">Sistem Informasi Sidamakmur | ©2025</p>
</div>
</body>
</html>
