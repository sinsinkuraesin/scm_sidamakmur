<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Penjualan</title>
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

        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .signature {
            text-align: right;
            margin-top: 40px;
        }

        .signature img {
            height: 80px;
            margin-bottom: 5px;
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
            .btn-container { display: none !important; }
            body { margin: 0; background-color: white; }
            .invoice-box { box-shadow: none; padding: 0; }
            .logo-kanan { top: 10px; right: 10px; }
        }
    </style>
</head>
<body>
@php
    $isPdf = request()->routeIs('jual.invoice.pdf');
    $logoPath = $isPdf
        ? public_path('images/logo.png')
        : asset('images/logo.png');

    $noTlp = $jual->konsumen->no_tlp ?? '';
    $waNumber = Str::startsWith($noTlp, '0') ? '62' . substr($noTlp, 1) : $noTlp;

    $pesan = urlencode("Halo {$jual->konsumen->nama_konsumen}, berikut kami berikan invoice pembelanjaan Anda di *PD Sidamakmur*:\n\n" .
    "No Invoice: INV-JUAL-{$jual->jual_id}\n📅 Tanggal: " . \Carbon\Carbon::parse($jual->tgl_jual)->format('d M Y') . "\n" .
    "Total Pembelian: Rp " . number_format($jual->detailJual->sum('total'), 0, ',', '.') . "\n\n" .
    "Silakan lakukan pembayaran melalui transfer ke salah satu rekening berikut:\n\n" .
    "*Bank BRI*\nNo. Rek: 1234 5678 9101 1121\nA.n: Nama Pemilik\n\n" .
    "*Bank Mandiri*\nNo. Rek: 9876 5432 1098 7654\nA.n: Nama Pemilik\n\n" .
    "Mohon melakukan pembayaran paling lambat hari ini sebelum sore hari ya.\n\n" .
    "Jika melakukan pembayaran secara *tunai (cash)*, mohon tetap lakukan konfirmasi melalui WhatsApp ini ya 🙏\n\n" .
    "Terima kasih telah berbelanja di *PD Sidamakmur*.");
    $waLink = "https://wa.me/$waNumber?text=$pesan";
@endphp

<div class="invoice-box">

    <!-- Header -->
    <div class="invoice-header-top">
        <div class="header-tengah">
            <h2>PD. SIDAMAKMUR</h2>
            <p>Blok. Kadutilu, Dukupuntang Cirebon - 45652</p>
            <p>Telp. 085317889229</p>
        </div>

        <div class="logo-kanan">
            <img src="{{ $logoPath }}" alt="Logo">
        </div>
        <div class="line"></div>
    </div>

    <!-- Invoice Detail -->
    <div class="invoice-header">
        <h4 class="invoice-title">INVOICE PENJUALAN</h4>
        <p class="mb-0">No: INV-JUAL-{{ $jual->jual_id }}</p>
        <p class="mb-0">Tanggal: {{ \Carbon\Carbon::parse($jual->tgl_jual)->format('d M Y') }}</p>
    </div>

    <div class="section-title">Informasi Konsumen</div>
    <table style="width: auto;">
        <tr>
            <td><strong>Nama Konsumen:</strong></td>
            <td>{{ $jual->konsumen->nama_konsumen ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Pasar Tujuan:</strong></td>
            <td>{{ $jual->nama_pasar ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Detail Penjualan</div>
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
            @foreach ($jual->detailJual as $item)
            <tr>
                <td>{{ $item->ikan->jenis_ikan ?? '-' }}</td>
                <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                <td>{{ $item->jml_ikan }}</td>
                <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Penjualan: Rp {{ number_format($jual->detailJual->sum('total'), 0, ',', '.') }}
    </div>

    <div class="signature">
        <p>Hormat Kami,</p>
        <img src="{{ $isPdf ? public_path('images/ttd.jpg') : asset('images/ttd.jpg') }}" alt="Tanda Tangan">
        <p><strong>PD Sidamakmur</strong></p>
    </div>

    @if (!request()->routeIs('jual.invoice.pdf'))
    <div class="btn-container">
        <a href="{{ route('jual.index') }}" class="btn">Kembali</a>
        <a href="{{ route('jual.invoice.pdf', $jual->jual_id) }}" class="btn" target="_blank">Cetak PDF</a>
        <a href="{{ $waLink }}" target="_blank" class="btn" style="background-color: #25D366;">Kirim via WhatsApp</a>
    </div>
    @endif

    <p class="footer-note">Sistem Informasi Sidamakmur | ©2025</p>
</div>
</body>
</html>
