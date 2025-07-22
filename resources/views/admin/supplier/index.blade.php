@extends('admin.layout')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Supplier</h4>
                        <div class="float end">
                            <a class="btn btn-primary mb-3 ml-3" href="{{ route('supplier.create') }}">Tambah Data Supplier</a>
                        </div>

                        <form action="/carisp" method="GET">
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

                        @if ($message = Session::get('success'))
                            <div class=" m-2 alert alert-primary">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <div class="table-responsive pt-3">
                            <table class="display expandable-table table-flush" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Supplier</th>
                                        <th>Nama Supplier</th>
                                        <th>Jenis Ikan</th>
                                        <th>Alamat Supplier</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suppliers as $supplier)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $supplier->kd_supplier }}</td>
                                            <td>{{ $supplier->nm_supplier }}</td>
                                            <td>{{ $supplier->jenis_ikan }}</td>
                                            <td>{{ $supplier->alamat }}</td>

                                            <td>
                                                <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="{{ route('supplier.edit', $supplier->id) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    <button type="submit" class="btn btn-danger"
                                                        onclick="return confirm('Apakah Anda Ingin Menghapus Data Supplier..?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                @endsection
            </div>
