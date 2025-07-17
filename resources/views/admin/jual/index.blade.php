@extends('admin.layout')
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Transaksi Penjualan</h4>
                    <div class="float-end">
                        <a class="btn btn-primary mb-3" href="{{ route('jual.create') }}">Tambah Data Penjualan</a>
                    </div>

                    <form action="/cariju" method="GET">
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
                        <form action="/carij" method="GET" class="mb-3">
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
                    <div class="mb-3 text-end">
                        <h5 class="fw-bold">
                            Total Pendapatan:
                            <span class="text-dark">
                                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                            </span>
                        </h5>
                    </div>

                    <table class="display expandable-table table-flush" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Konsumen</th>
                                <th>Nama Pasar</th>
                                <th>Tanggal Jual</th>
                                <th>Jenis Ikan</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($juals as $jual)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $jual->konsumen->nama_konsumen }}</td>
                                    <td>{{ $jual->nama_pasar }}</td>
                                    <td>{{ $jual->tgl_jual }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($jual->detailJual as $detail)
                                                <li>{{ $detail->ikan->jenis_ikan }} - {{ $detail->jml_ikan }} Kg</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        Rp {{ number_format($jual->detailJual->sum('total'), 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <form action="{{ route('jual.destroy', $jual->jual_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{ route('jual.edit', $jual->jual_id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="{{ route('jual.invoice', $jual->jual_id) }}" class="btn btn-info btn-sm" target="_blank">Invoice</a>
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                    <div class="card-footer">{{ $juals->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
