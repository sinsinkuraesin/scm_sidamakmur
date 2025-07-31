@extends('pemilik.layout')
@section('content')

<div class="content-wrapper px-2">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Data Pembelian Ikan</h4>

                    {{-- Form Pencarian --}}
                    <form action="{{ route('pemilik.pembelian') }}" method="GET" class="d-flex align-items-center mb-4">
                        <div class="me-2">
                            <label for="kata" class="form-label mb-3 fw-bold">Cari Pembelian</label>
                            <input type="text" name="kata" id="kata" class="form-control" value="{{ request('kata') }}" placeholder="Cari kode, supplier, jenis ikan..." required>
                        </div>
                        <div class="mt-5 pt-1">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-magnify"></i> Cari
                            </button>
                        </div>
                    </form>

                    {{-- Filter Tanggal --}}
                    <form action="{{ route('pemilik.pembelian') }}" method="GET" class="row mb-4">
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
                                    <th>Kode Pembelian</th>
                                    <th>Supplier</th>
                                    <th>Jenis Ikan</th>
                                    <th>Tanggal Beli</th>
                                    <th>Jumlah (Kg)</th>
                                    <th>Harga Satuan</th>
                                    <th>Total Harga</th>
                                    <th>Status Pembayaran</th>
                                    <th>Invoice</th>
                                </tr>
                            </thead>
                            <tbody class="text-center align-middle">
                                @forelse ($belis as $i => $beli)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $beli->kd_beli }}</td>
                                        <td>{{ $beli->supplier->nm_supplier ?? '-' }}</td>
                                        <td>{{ $beli->ikan->jenis_ikan ?? '-' }}</td>
                                        <td>{{ $beli->tgl_beli }}</td>
                                        <td>{{ $beli->jml_ikan }} Kg</td>
                                        <td>Rp {{ number_format($beli->harga_beli, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($beli->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($beli->bukti_pembayaran)
                                                <span class="badge bg-success">Sudah Bayar</span>
                                            @else
                                                <span class="badge bg-danger">Belum Bayar</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('beli.invoice', $beli->id) }}" class="btn btn-info btn-sm" target="_blank">Lihat</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">Data tidak ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            @if ($belis->count())
                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-end fw-bold">Total Pengeluaran:</td>
                                    <td colspan="3" class="fw-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
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
