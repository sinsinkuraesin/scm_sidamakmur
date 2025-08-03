@extends('pemilik.layout')
@section('content')

<div class="content-wrapper px-2">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Data Penjualan Ikan</h4>

                    {{-- Form Pencarian --}}
                    <form action="{{ route('pemilik.penjualan') }}" method="GET" class="d-flex align-items-center mb-4">
                        <div class="me-2">
                            <label for="kata" class="form-label mb-3 fw-bold">Cari Penjualan</label>
                            <input type="text" name="kata" id="kata" class="form-control" value="{{ request('kata') }}" placeholder="Cari kode, konsumen, jenis ikan..." required>
                        </div>
                        <div class="mt-5 pt-1">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-magnify"></i> Cari
                            </button>
                        </div>
                    </form>

                    {{-- Filter Tanggal --}}
                    <form action="{{ route('pemilik.penjualan') }}" method="GET" class="row mb-4">
                        <div class="col-md-4">
                            <label for="dari" class="form-label fw-bold">Dari Tanggal</label>
                            <input type="date" name="dari" id="dari" class="form-control" value="{{ request('dari') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="sampai" class="form-label fw-bold">Sampai Tanggal</label>
                            <input type="date" name="sampai" id="sampai" class="form-control" value="{{ request('sampai') }}">
                        </div>
                        <div class="col-md-4 mt-4 pt-1">
                            <button type="submit" class="btn btn-secondary">Filter</button>
                        </div>
                    </form>

                    {{-- Notifikasi --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    {{-- Tabel Data --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="custom-header text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Penjualan</th>
                                    <th>Nama Konsumen</th>
                                    <th>Jenis Ikan</th>
                                    <th>Tanggal Jual</th>
                                    <th>Jumlah Ikan (Kg)</th>
                                    <th>Harga/Kg</th>
                                    <th>Total Harga</th>
                                    <th>Invoice</th>
                                </tr>
                            </thead>
                            <tbody class="text-center align-middle">
                                @forelse ($juals as $i => $jual)
                                    @foreach ($jual->detailJual as $detail)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $jual->kd_jual }}</td>
                                        <td>{{ $jual->konsumen->nama_konsumen ?? '-' }}</td>
                                        <td>{{ $detail->ikan->jenis_ikan ?? '-' }}</td>
                                        <td>{{ $jual->tgl_jual }}</td>
                                        <td>{{ $detail->jml_ikan }} Kg</td>
                                        <td>Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('penjualan.invoice', $jual->jual_id) }}" class="btn btn-info btn-sm mt-3" target="_blank">Cetak</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="9">Data tidak ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($totalPendapatan > 0)
                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-end fw-bold">Total Pendapatan:</td>
                                    <td colspan="2" class="fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                            @endif
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    thead.custom-header {
        background: linear-gradient(90deg, #1d976c, #93f9b9);
        color: white;
    }

    table.table-bordered th,
    table.table-bordered td {
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .table th, .table td {
            font-size: 14px;
        }
    }
</style>

@endsection
