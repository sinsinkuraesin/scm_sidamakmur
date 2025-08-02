<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Pembelian</title>
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

        /* Logo di kanan atas */
        .logo-kanan {
            position: absolute;
            top: 30px;
            right: 30px;
        }

        .logo-kanan img {
            height: 60px;
        }

        /* Header teks di tengah */
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
            margin: 20px 0 20px 0; /* Sebelumnya 80px, sekarang jadi 20px agar lebih dekat */
        }


        .invoice-header {
            text-align: right;
        }


        .invoice-title {
            font-size: 24px;
            color: #3f51b5;
            margin: 0;
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
        }

        td, th {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #3f51b5;
            color: white;
        }

        .btn-container {
            text-align: center;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 10px 25px;
            margin: 0 10px;
            background-color: #3f51b5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .btn:hover {
            background-color: #2c3e90;
        }

        .footer-note {
            text-align: center;
            color: #888;
            font-size: 12px;
            margin-top: 30px;
        }

        @media print {
            .btn-container {
                display: none !important;
            }

            body {
                margin: 0;
                background-color: white;
            }

            .invoice-box {
                box-shadow: none;
                padding: 0;
            }

            .logo-kanan {
                top: 10px;
                right: 10px;
            }
        }
    </style>
</head>
<body>

@php
    $isPdf = request()->routeIs('beli.invoice.pdf');
    $logoPath = $isPdf
        ? public_path('images/logo.png')
        : asset('images/logo.png');
@endphp

<div class="invoice-box">

    <div class="invoice-header-top">
        <!-- Header Tengah -->
        <div class="header-tengah">
            <h2>PD. SIDAMAKMUR</h2>
            <p>Blok. Kadutilu, Dukupuntang Cirebon - 45652</p>
            <p>Telp. 085317889229</p>
        </div>

        <!-- Logo Kanan -->
        <div class="logo-kanan">
            <img src="{{ $logoPath }}" alt="Logo">
        </div>
        <div class="line"></div>
    </div>

    <!-- Invoice detail -->
    <div class="invoice-header">
        <h4 class="text-primary fw-bold">INVOICE PEMBELIAN</h4>
        <p class="mb-0">No: INV-BELI-{{ $beli->id }}</p>
        <p class="mb-0">Tanggal: {{ \Carbon\Carbon::parse($beli->tgl_beli)->format('d M Y') }}</p>
    </div>


    <div class="section-title">Informasi Supplier</div>
    <table style="width: auto;">
        <tr>
            <td><strong>Nama Supplier:</strong></td>
            <td>{{ $beli->supplier->nm_supplier ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Alamat Supplier:</strong></td>
            <td>{{ $beli->supplier->alamat ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Detail Pembelian</div>
    <table border="1">
        <thead>
            <tr>
                <th>Jenis Ikan</th>
                <th>Harga / Kg</th>
                <th>Jumlah (Kg)</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $beli->ikan->jenis_ikan ?? '-' }}</td>
                <td>Rp {{ number_format($beli->harga_beli, 0, ',', '.') }}</td>
                <td>{{ $beli->jml_ikan }} Kg</td>
                <td>Rp {{ number_format($beli->total_harga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Status Pembayaran</div>
    <p>
        @if ($beli->bukti_pembayaran)
            <span style="color: green; font-weight: bold;">Sudah Upload Bukti Pembayaran</span>
        @else
            <span style="color: red; font-weight: bold;">Belum Upload Bukti Pembayaran</span>
        @endif
    </p>

    @if (!request()->routeIs('beli.invoice.pdf'))
    <div class="btn-container">
        <a href="{{ route('beli.index') }}" class="btn">Selesai</a>
        <a href="{{ route('beli.invoice.pdf', $beli->id) }}" class="btn" target="_blank">Cetak PDF</a>
    </div>
    @endif

    <p class="footer-note">Sistem Informasi Sidamakmur | ©2025</p>
</div>

</body>
</html>
