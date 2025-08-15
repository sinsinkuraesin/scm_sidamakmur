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
            font-size: 14px;
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

        .invoice-header {
            text-align: center;
        }

        .logo-kanan {
            position: absolute;
            top: 30px;
            left: 30px;
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

        .section-title {
            font-weight: bold;
            color: #3f51b5;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #3f51b5;
            color: white;
        }

        .total-row {
            font-weight: bold;
            background-color: #f1f1f1;
        }

        .signature {
            margin-top: 60px;
            text-align: right;
        }

        .signature img {
            width: 120px;
            margin-bottom: 10px;
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

    $judul = 'Laporan Penjualan Periode';
    if ($filter === 'bulan') {
    $startOfMonth = Carbon::parse($tanggal)->startOfMonth();
    $endOfMonth   = Carbon::parse($tanggal)->endOfMonth();
    $periode = $startOfMonth->translatedFormat('j F Y') . ' - ' . $endOfMonth->translatedFormat('j F Y');
    } elseif ($filter === 'tahun') {
        $startOfYear = Carbon::parse($tanggal)->startOfYear();
        $endOfYear   = Carbon::parse($tanggal)->endOfYear();
        $periode = $startOfYear->translatedFormat('j F Y') . ' - ' . $endOfYear->translatedFormat('j F Y');

    } else {
        $periode = Carbon::parse($tanggal)->translatedFormat('j F Y');
    }

    $logoPath = public_path('images/logo.png');
    $ttdPath = public_path('images/ttd.jpg');
@endphp

<div class="invoice-box">
    <div class="header-tengah">
        <h2>PD. SIDAMAKMUR</h2>
        <p>Blok. Kadutilu, Dukupuntang Cirebon - 45652</p>
        <p>Telp. 085317889229</p>
    </div>

    <div class="logo-kanan">
        <img src="file://{{ $logoPath }}" alt="Logo">
    </div>

    <div class="line"></div>

    <div style="text-align:center; font-family:Arial, sans-serif;">
        <h2 style="margin:0;">Laporan Penjualan</h2>
        <p style="margin:5; font-size:16px;">Periode {{ $periode }}</p>
    </div>

    <div class="section-title">Detail Penjualan</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Konsumen</th>
                <th>Jenis Ikan</th>
                <th>Jumlah Ikan (Kg)</th>
                <th>Harga/Kg</th>
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
                        @php $count = count($jual->detailJual); @endphp
                        @foreach($jual->detailJual as $detail)
                            {{ $count > 1 ? '• ' : '' }}{{ $detail->ikan->jenis_ikan ?? '-' }}<br>
                        @endforeach
                    </td>
                    <td>
                        @php $count = count($jual->detailJual); @endphp
                        @foreach($jual->detailJual as $detail)
                            {{ $count > 1 ? '• ' : '' }}{{ $detail->jml_ikan }} Kg<br>
                        @endforeach
                    </td>
                    <td>
                        @php $count = count($jual->detailJual); @endphp
                        @foreach($jual->detailJual as $detail)
                            {{ $count > 1 ? '• ' : '' }}Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}<br>
                        @endforeach
                    </td>
                    <td>Rp {{ number_format($jual->detailJual->sum('total'), 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">Total Penjualan</td>
                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">Total Ikan Terjual</td>
                <td>{{ number_format($total_kg, 0, ',', '.') }} Kg</td>
            </tr>
        </tfoot>
    </table>

    <div class="signature">
        <p>Hormat Kami,</p>
        <img src="file://{{ $ttdPath }}" alt="Tanda Tangan">
        <p><strong>PD Sidamakmur</strong></p>
    </div>

    <p class="footer-note">Sistem Informasi Sidamakmur | ©2025</p>
</div>
</body>
</html>
