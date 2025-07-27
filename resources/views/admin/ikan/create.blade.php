@extends('admin.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Tambah Data Ikan</h4>
            <p class="card-description">
            Masukkan data ikan
            </p>
            <form class="user" method="POST" action="{{ route('ikan.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <Label>Kode Ikan:</Label>
                    <input type="text" class="form-control" name="kd_ikan">
                </div>

                <div class="form-group">
                          <Label>Upload Foto Ikan :</Label>
                          <input type="file" class="form-control" name="foto_ikan">
                </div>

                <div class="form-group">
                    <Label>Jenis Ikan:</Label>
                    <input type="text" class="form-control" name="jenis_ikan">
                </div>


                <div class="form-group">
                    <Label>Harga Beli/Kg:</Label>
                    <input type="text" class="form-control" name="harga_beli">
                </div>

                <div class="form-group">
                    <Label>Harga Jual/Kg:</Label><br>
                    <input type="text" class="form-control" name="harga_jual">
                </div>

                <div class="form-group">
                    <Label>Stok (Kg):</Label>
                    <input type="number" class="form-control" name="stok">
                </div>

                <button type="submit" class="btn btn-primary mr-2">Submit</button>
            </form>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
