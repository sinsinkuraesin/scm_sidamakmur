<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembelian</title>
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

        h1 {
            text-align: center;
            margin: 10px 0 30px;
            font-size: 20px;
            color: #3f51b5;
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

<div class="invoice-box">

    <!-- Header Perusahaan -->
    <div class="header-tengah">
        <h2>PD. SIDAMAKMUR</h2>
        <p>Blok. Kadutilu, Dukupuntang Cirebon - 45652</p>
        <p>Telp. 085317889229</p>
    </div>

    <div class="logo-kanan">
        <img src="file://{{ $logoPath }}" alt="Logo">
    </div>

    <div class="line"></div>

    <div class="invoice-header">
        <h4 class="text-primary fw-bold">{{ $judul }}</h4>
    </div>

    <!-- Tabel Pembelian -->
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

    <!-- Tanda Tangan -->
    <div class="signature">
        <p>Hormat Kami,</p>
        <img src="file://{{ $ttdPath }}" alt="Tanda Tangan">
        <p><strong>PD Sidamakmur</strong></p>
    </div>

    <!-- Catatan Kaki -->
    <p class="footer-note">Sistem Informasi Sidamakmur | ©2025</p>
</div>

</body>
</html>
