@extends('pemilik.layout')
@section('content')

<div class="content-wrapper px-2">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Laporan Pembelian</h4>

                    <form method="GET" action="{{ route('pemilik.lap_pembelian') }}" class="row mb-4">
                        <div class="col-md-3">
                            <label for="filter" class="form-label fw-bold">Filter</label>
                            <select name="filter" class="form-control" required>
                                <option value="harian" {{ $filter == 'harian' ? 'selected' : '' }}>Harian</option>
                                <option value="bulanan" {{ $filter == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="tahunan" {{ $filter == 'tahunan' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label fw-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" required>
                        </div>
                        <div class="col-md-4 mt-4 pt-1">
                            <button type="submit" class="btn btn-primary me-2">Tampilkan</button>
                            <a href="{{ route('pemilik.lap_pembelian.pdf', ['filter' => $filter, 'tanggal' => $tanggal]) }}"
                               target="_blank" class="btn btn-success">Cetak PDF</a>
                        </div>
                    </form>

                    <div class="table-responsive pt-3">
                        <table class="table table-hover table-bordered">
                            <thead class="text-center text-white" style="background: linear-gradient(90deg, #1d976c, #93f9b9);">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kode Supplier</th>
                                    <th>Jenis Ikan</th>
                                    <th>Jumlah Ikan (Kg)</th>
                                    <th>Harga/Kg</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
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
                                    <td colspan="6" class="text-end fw-bold">Total Pengeluaran</td>
                                    <td class="fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
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
