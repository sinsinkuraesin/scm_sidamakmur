@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Pembelian PD. Sidamakmur</h4>
                        <div class="float end">
                            <a class="btn btn-primary mb-3 ml-3" href="{{ route('beli.create') }}">Tambah Data Pembelian</a>
                        </div>

                        <form action="/caribe" method="GET">
                            @csrf
                            <div class="input-group col-7 col-5 mb-2">
                                <input type="text" name="kata" class="form-control bg-light border-1 small"
                                       placeholder="Cari Data?" aria-label="Search" aria-describedby="basic-addon2"
                                       style="border-color: #3f51b5;" required>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" name="submit" value="Cari Data">
                                        <i class="fas fa-search fa-sm"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                        <form action="/carib" method="GET" class="mb-3">
                            @csrf
                            <div class="form-row row">
                                <div class="col-md-3">
                                    <input type="date" name="dari" class="form-control" placeholder="Dari tanggal">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="sampai" class="form-control" placeholder="Sampai tanggal">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Cari</button>
                                </div>
                            </div>
                        </form>

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">{{ $message }}</div>
                        @endif

                        <div class="table-responsive pt-3">
                            <table class="display expandable-table table-flush" style="width:100%">
                        <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Pembelian</th>
                                    <th>Nama Supplier</th>
                                    <th>Jenis Ikan</th>
                                    <th>Tanggal Beli</th>
                                    <th>Jumlah Ikan</th>
                                    <th>Harga/Kg</th>
                                    <th>Total Harga</th>
                                    <th>Status Pembayaran</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($belis as $beli)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $beli->kd_beli }}</td>
                                        <td>{{ $beli->supplier->nm_supplier ?? '-' }}</td>
                                        <td>{{ $beli->ikan->jenis_ikan ?? '-' }}</td>
                                        <td>{{ $beli->tgl_beli }}</td>
                                        <td>{{ $beli->jml_ikan }} Kg</td>
                                        <td>Rp. {{ number_format($beli->harga_beli, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($beli->total_harga, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($beli->bukti_pembayaran)
                                                <span class="badge bg-success text-white">Sudah Upload Bukti Bayar</span>
                                            @else
                                                <span class="badge bg-danger text-white">Belum Upload Bukti Bayar</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('beli.destroy', $beli->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('beli.edit', $beli->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')">Hapus</button>
                                                <a href="{{ route('beli.invoice.pdf', $beli->id) }}" class="btn btn-info btn-sm" target="_blank">Cetak</a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-right font-weight-bold">Total Pengeluaran:</td>
                                    <td colspan="1" class="font-weight-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>

                        </table>

                        </div>
                        <div class="card-footer">{{ $belis->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
