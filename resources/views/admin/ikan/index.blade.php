@extends('admin.layout')
@section('content')

<div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Ikan Sidamakmur</h4>
                        <div class="float end">
                            <a class="btn btn-primary mb-3 ml-3"  href="{{ route('ikan.create') }}">Tambah Data Ikan</a>
                        </div>

                        <form action="/cari" method="GET">
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
                                    <div class="alert alert-primary">
                                        <p>{{ $message }}</p>
                                    </div>
                        @endif

                        <div class="table-responsive pt-3">
                            <table class="display expandable-table table-flush" style="width:100%">
                               <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Ikan</th>
                                        <th>Harga Beli/Kg</th>
                                        <th>Harga Jual/Kg</th>
                                        <th>Stok (Kg)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $ikans as $ikan )
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $ikan->jenis_ikan }}</td>
                                        <td>Rp. {{ number_format($ikan->harga_beli, 0, ',', '.') }} /kg</td>
                                        <td>Rp. {{ number_format($ikan->harga_jual, 0, ',', '.') }} /kg</td>
                                        <td>{{ $ikan->stok }} kg</td>

                                        <td>
                                            <form action="{{ route('ikan.destroy',$ikan->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('ikan.edit',$ikan->id) }}" class="btn btn-warning">Edit</a>
                                                <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Apakah Anda Ingin Menghapus Data Ikan..?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
