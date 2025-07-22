@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Laporan Penjualan</h4>

                    <form method="GET" action="{{ route('laporan.penjualan') }}" class="form-inline mb-4">
                        <div class="form-group mr-2">
                            <select name="filter" class="form-control" required>
                                <option value="hari" {{ $filter == 'jenis' ? 'selected' : '' }}>Jumlah Ikan</option>
                                <option value="hari" {{ $filter == 'hari' ? 'selected' : '' }}>Harian</option>
                                <option value="bulan" {{ $filter == 'bulan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="tahun" {{ $filter == 'tahun' ? 'selected' : '' }}>Pertahun</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Tampilkan</button>
                        <a href="{{ route('laporan.penjualan.cetak', ['filter' => $filter, 'tanggal' => $tanggal]) }}" class="btn btn-success" target="_blank">
                            Cetak PDF
                        </a>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Nama Konsumen</th>
                                    <th>Jenis Ikan</th>
                                    <th>Jumlah</th>
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
                                            <ul class="mb-0 pl-3">
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
                                        <td colspan="6" class="text-center">Tidak ada data penjualan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right font-weight-bold">Total Penjualan</td>
                                    <td class="font-weight-bold">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
