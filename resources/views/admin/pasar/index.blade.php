@extends('admin.layout')
@section('content')

<div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Pasar</h4>
                        <div class="float end">
                            <a class="btn btn-primary mb-3 ml-3"  href="{{ route('pasar.create') }}">Tambah Data Pasar</a>
                        </div>

                        <form action="/carips" method="GET">
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
                                        <th>Kode Pasar</th>
                                        <th>Nama Pasar</th>
                                        <th>Alamat</th>
                                        <th>Jam Operasional</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $pasars as $pasar )
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $pasar->kd_pasar }}</td>
                                        <td>{{ $pasar->nama_pasar }}</td>
                                        <td>{{ $pasar->alamat }}</td>
                                        <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $pasar->jam_buka)->format('H:i') }} - {{ \Carbon\Carbon::createFromFormat('H:i:s', $pasar->jam_tutup)->format('H:i') }} WIB</td>

                                        <td>
                                            <form action="{{ route('pasar.destroy',$pasar->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a href="{{ route('pasar.edit',$pasar->id) }}" class="btn btn-warning">Edit</a>
                                                <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Apakah Anda Ingin Menghapus Data pasar..?')">Hapus</button>
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
