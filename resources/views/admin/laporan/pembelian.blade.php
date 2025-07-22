@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Laporan Pembelian</h4>

                    <form method="GET" action="{{ route('laporan.pembelian') }}" class="form-inline mb-4">
                        <div class="form-group mr-2">
                            <select name="filter" class="form-control" required>
                                <option value="harian" {{ $filter == 'jenis' ? 'selected' : '' }}>Jenis Ikan</option>
                                    <option value="harian" {{ $filter == 'harian' ? 'selected' : '' }}>Jenis Ikan</option>
                                <option value="harian" {{ $filter == 'harian' ? 'selected' : '' }}>Harian</option>
                                <option value="bulanan" {{ $filter == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="tahunan" {{ $filter == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Tampilkan</button>
                        <a href="{{ route('laporan.pembelian.pdf', ['filter' => $filter, 'tanggal' => $tanggal]) }}" target="_blank" class="btn btn-success">Cetak PDF</a>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
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
                                @forelse ($belis as $beli)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $beli->tgl_beli }}</td>
                                        <td>{{ $beli->supplier->kd_supplier ?? '-' }}</td>
                                        <td>{{ $beli->ikan->jenis_ikan ?? '-' }}</td>
                                        <td>{{ $beli->jml_ikan }} Kg</td>
                                        <td>Rp {{ number_format($beli->harga_beli, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($beli->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data pembelian</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-right font-weight-bold">Total Pengeluaran</td>
                                    <td class="font-weight-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
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
