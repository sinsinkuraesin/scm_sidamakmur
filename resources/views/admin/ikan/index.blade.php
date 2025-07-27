@extends('admin.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Data Ikan Sidamakmur</h4>

                    <div class="float-end">
                        <a class="btn btn-primary mb-3 ml-3" href="{{ route('ikan.create') }}">Tambah Data Ikan</a>
                    </div>


                    {{-- Form pencarian kata --}}
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

                    {{-- Card Pencarian Stok Per Tanggal --}}
                    <div class="card-body">
                        <form method="GET" action="{{ route('ikan.index') }}">
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-4">
                                    <label for="tanggal"><i class="far fa-calendar"></i> Pencarian Stok Per tanggal</label>
                                    <input type="date" id="tanggal" name="tanggal" class="form-control"
                                        value="{{ request('tanggal') ?? date('Y-m-d') }}" required>
                                </div>
                                <div class="form-group col-md-2">
                                    <button class="btn btn-primary" type="submit" name="submit" value="Cari Data">
                                    <i class="fas fa-search fa-sm"></i> Tampilkan
                                </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- Flash message --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-primary">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    {{-- Tabel ikan --}}
                    <div class="table-responsive pt-3">
                        <table class="display expandable-table table-flush" style="width:100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Ikan</th>
                                    <th>Foto Ikan</th>
                                    <th>Jenis Ikan</th>
                                    <th>Harga Beli/Kg</th>
                                    <th>Harga Jual/Kg</th>
                                    <th>Stok (Kg)</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ikans as $ikan)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $ikan->kd_ikan }}</td>
                                    <td>
                                        <img src="{{ asset('Foto_Ikan/' . $ikan->foto_ikan) }}" alt="Foto Ikan" width="100">
                                    </td>
                                    <td>{{ $ikan->jenis_ikan }}</td>
                                    <td>Rp. {{ number_format($ikan->harga_beli, 0, ',', '.') }} /kg</td>
                                    <td>Rp. {{ number_format($ikan->harga_jual, 0, ',', '.') }} /kg</td>
                                    <td>
                                        @if(request('tanggal'))
                                            {{ $ikan->stok_per_tanggal }} kg<br>
                                            <small class="text-muted">per {{ request('tanggal') }}</small>
                                        @else
                                            {{ $ikan->stok }} kg
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('ikan.destroy',$ikan->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <a href="{{ route('ikan.edit',$ikan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <a href="{{ route('beli.create') }}" class="btn btn-success btn-sm">Beli</a>
                                            <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda Ingin Menghapus Data Ikan..?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> <!-- End table responsive -->

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
