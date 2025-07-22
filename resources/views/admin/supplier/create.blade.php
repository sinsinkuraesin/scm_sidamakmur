@extends('admin.layout')
@section('content')
<div class="col-lg-12 mb-4">
    <div class="content-wrapper">
        <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Data Supplier</h4>
                <p class="card-description">Masukkan data supplier</p>
                <form class="user" method="POST" action="{{ route('supplier.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <Label>Kode Supplier:</Label>
                        <input type="text" class="form-control" name="kd_supplier">
                    </div>

                    <div class="form-group">
                        <Label>Nama Supplier:</Label>
                        <input type="text" class="form-control" name="nm_supplier">
                    </div>

                    <div class="form-group">
                        <label for="jenis_ikan">Pilih Jenis Ikan:</label>
                        <select name="jenis_ikan" id="jenis_ikan" class="form-control" required>
                            <option value="" disabled selected>Pilih Jenis Ikan</option>
                            @foreach ($ikan as $item)
                            <option value="{{ $item->id }}">{{ $item->jenis_ikan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <Label>Alamat Supplier:</Label><br>
                        <input type="text" class="form-control" name="alamat">
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>



@endsection
