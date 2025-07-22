<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Pembelian</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: auto;
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
            margin: 0;
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

        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
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
        }
    </style>
</head>
<body>

    @php
        $isPdf = request()->routeIs('beli.invoice.pdf');
        $logoPath = $isPdf
            ? public_path('images/logo.png')  // untuk cetak PDF
            : asset('images/logo.png');       // untuk tampilan di browser
    @endphp

    <div class="invoice-box" style="padding: 30px;">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <img src="{{ $logoPath }}" alt="Logo" style="height: 60px;">
            </div>
            <div class="text-end">
                <h4 class="text-primary fw-bold">INVOICE PEMBELIAN</h4>
                <p class="mb-0">No: INV-BELI-{{ $beli->id }}</p>
                <p class="mb-0">Tanggal: {{ \Carbon\Carbon::parse($beli->tgl_beli)->format('d M Y') }}</p>
            </div>
        </div>

        <hr>

        <div class="section-title">Informasi Supplier</div>
        <table style="width: auto;">
            <tr>
                <td style="padding-right: 10px;"><strong>Kode Supplier:</strong></td>
                <td>{{ $beli->supplier->kd_supplier ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding-right: 10px;"><strong>Alamat Supplier:</strong></td>
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
                    <td>{{ $beli->jml_ikan }}</td>
                    <td>Rp {{ number_format($beli->total_harga, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        @if ($beli->bukti_pembayaran)
            <div class="section-title">Bukti Pembayaran</div>
            <div style="text-align: center; margin-top: 15px;">
                <img src="{{ $isPdf ? public_path('storage/' . $beli->bukti_pembayaran) : asset('storage/' . $beli->bukti_pembayaran) }}"
                    alt="Bukti Pembayaran" style="max-width: 100%; max-height: 400px;">
            </div>
        @endif


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
