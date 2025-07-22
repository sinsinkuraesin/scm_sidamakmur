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
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-header img {
            height: 60px;
        }
        .invoice-title {
            font-size: 24px;
            color: #3f51b5;
        }
        hr {
            border: 0;
            border-top: 2px solid #3f51b5;
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
        .payment-info {
            margin-top: 30px;
            background-color: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            border-left: 5px solid #4caf50;
            font-size: 14px;
            color: #2e7d32;
        }
        .payment-info a {
            color: #2e7d32;
            text-decoration: underline;
        }
        @media print {
            .btn-container { display: none !important; }
            body { margin: 0; background-color: white; }
            .invoice-box { box-shadow: none; padding: 0; }
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
        if (Str::startsWith($noTlp, '0')) {
            $waNumber = '62' . substr($noTlp, 1);
        } else {
            $waNumber = $noTlp;
        }

       $pesan = urlencode("Halo *{$jual->konsumen->nama_konsumen}*, berikut adalah ringkasan invoice pembelian Anda di *PD Sidamakmur*:\n\n" .
            "🧾 No Invoice: INV-JUAL-{$jual->jual_id}\n📅 Tanggal: " . \Carbon\Carbon::parse($jual->tgl_jual)->format('d M Y') . "\n" .
            "💰 Total Pembelian: Rp " . number_format($jual->detailJual->sum('total'), 0, ',', '.') . "\n\n" .
            "Silakan lakukan pembayaran melalui transfer ke salah satu rekening berikut:\n\n" .
            "🏦 *Bank BRI*\nNo. Rek: 1234 5678 9101 1121\nA.n: Nama Pemilik\n\n" .
            "🏦 *Bank Mandiri*\nNo. Rek: 9876 5432 1098 7654\nA.n: Nama Pemilik\n\n" .
            "Setelah transfer, mohon konfirmasi melalui WhatsApp ini ya 🙏\n\n" .
            "Terima kasih telah berbelanja di *PD Sidamakmur*.");
        $waLink = "https://wa.me/$waNumber?text=$pesan";

    @endphp

    <div class="invoice-box">
        <div class="invoice-header">
            <img src="{{ $logoPath }}" alt="Logo">
            <div>
                <h4 class="invoice-title">INVOICE PENJUALAN</h4>
                <p>No: INV-JUAL-{{ $jual->jual_id }}</p>
                <p>Tanggal: {{ \Carbon\Carbon::parse($jual->tgl_jual)->format('d M Y') }}</p>
            </div>
        </div>

        <hr>

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

        <div class="total" style="text-align:right; font-weight:bold;">
            Total Penjualan: Rp {{ number_format($jual->detailJual->sum('total'), 0, ',', '.') }}
        </div>

        <div class="payment-info">
        <strong>Silakan melakukan pembayaran melalui transfer ke rekening berikut:</strong><br><br>

        <strong>Bank BRI</strong><br>
        No. Rekening: 1234 5678 9101 1121<br>
        a.n. Nama Pemilik Rekening<br><br>

        <strong>Bank Mandiri</strong><br>
        No. Rekening: 9876 5432 1098 7654<br>
        a.n. Nama Pemilik Rekening<br><br>

        Jika Anda ingin melakukan pembayaran secara <strong>tunai (cash)</strong>, harap konfirmasi terlebih dahulu melalui <strong><a href="https://wa.me/6281234567890" target="_blank">WhatsApp</a></strong>.
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
