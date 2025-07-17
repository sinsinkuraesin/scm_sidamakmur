<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2, p {
            text-align: center;
            margin: 0;
        }
    </style>
</head>
<body>
       @php
    use Carbon\Carbon;

    Carbon::setLocale('id');

    $judul = 'Laporan Penjualan';

    if ($filter === 'bulan') {
        $bulan = Carbon::parse($tanggal)->translatedFormat('F');
        $judul .= ' - Bulan ' . $bulan;
    } elseif ($filter === 'tahun') {
        $tahun = Carbon::parse($tanggal)->translatedFormat('Y');
        $judul .= ' - Tahun ' . $tahun;
    } else {
        $tgl = Carbon::parse($tanggal)->translatedFormat('d F Y');
        $judul .= ' - Tanggal ' . $tgl;
    }
@endphp

<h2>{{ $judul }}</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Konsumen</th>
                <th>Jenis Ikan</th>
                <th>jml_ikan</th>
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
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Total Penjualan</strong></td>
                <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td colspan="5" style="text-align: right;"><strong>Total Ikan Terjual</strong></td>
                <td><strong>{{ number_format($total_kg, 0, ',', '.') }} Kg</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
