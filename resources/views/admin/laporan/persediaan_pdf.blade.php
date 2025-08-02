<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok</title>
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
            margin: 20px 0 20px 0;
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

        $judul = 'Laporan Stok Persediaan Tanggal ' . Carbon::parse($tanggal)->translatedFormat('d F Y');
        $isPdf = request()->routeIs('laporan.stok.pdf');
        $logoPath = $isPdf ? public_path('images/logo.png') : asset('images/logo.png');
    @endphp

    <div class="invoice-box">
        <div class="header-tengah">
            <h2>PD. SIDAMAKMUR</h2>
            <p>Blok. Kadutilu, Dukupuntang Cirebon - 45652</p>
            <p>Telp. 085317889229</p>
        </div>

        <div class="logo-kanan">
            <img src="file://{{ public_path('images/logo.png') }}" alt="Logo">
        </div>

        <div class="line"></div>

        <div class="invoice-header">
            <h4 class="text-primary fw-bold">{{ $judul }}</h4>
        </div>

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
                        <td>{{ number_format($item['masuk']) }}Kg</td>
                        <td>{{ number_format($item['keluar']) }}Kg</td>
                        <td>{{ number_format($item['stok_akhir']) }}Kg</td>
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
            <img src="file://{{ public_path('images/ttd.jpg') }}" alt="Tanda Tangan">
            <p><strong>PD Sidamakmur</strong></p>
        </div>

        <p class="footer-note">Sistem Informasi Sidamakmur | ©2025</p>
    </div>
</body>
</html>
