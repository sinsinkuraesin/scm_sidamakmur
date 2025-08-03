@extends('pemilik.layout')
@section('content')

<div class="content-wrapper px-2">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Laporan Penjualan</h4>

                    <form method="GET" action="{{ route('pemilik.lap_penjualan') }}" class="row mb-4">
                        <div class="col-md-3">
                            <label for="filter" class="form-label fw-bold">Filter</label>
                            <select name="filter" class="form-control" required>
                                <option value="hari" {{ $filter == 'hari' ? 'selected' : '' }}>Harian</option>
                                <option value="bulan" {{ $filter == 'bulan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="tahun" {{ $filter == 'tahun' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
                        </div>
                        <div class="col-md-4 mt-4 pt-1">
                            <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
                            <a href="{{ route('pemilik.lap_penjualan.pdf', ['filter' => $filter, 'tanggal' => $tanggal]) }}"
                               target="_blank" class="btn btn-success">Cetak PDF</a>
                        </div>
                    </form>

                    <div class="table-responsive pt-3">
                        <table class="table table-hover table-bordered">
                            <thead class="text-center text-white" style="background: linear-gradient(90deg, #1d976c, #93f9b9);">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Konsumen</th>
                                    <th>Jenis Ikan</th>
                                    <th>Jumlah Ikan (Kg)</th>
                                    <th>Harga/Kg</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @forelse ($penjualans as $jual)
                                    @foreach ($jual->detailJual as $detail)
                                        <tr>
                                            <td>{{ $loop->parent->iteration }}</td>
                                            <td>{{ $jual->tgl_jual }}</td>
                                            <td>{{ $jual->konsumen->nm_konsumen ?? '-' }}</td>
                                            <td>{{ $detail->ikan->jenis_ikan ?? '-' }}</td>
                                            <td>{{ $detail->jml_ikan }} Kg</td>
                                            <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data penjualan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-end fw-bold">Total Pendapatan</td>
                                    <td class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</td>
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
