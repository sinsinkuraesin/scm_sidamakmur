@extends('admin.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Tambah Data Pasar</h4>
            <p class="card-description">
            Masukkan data pasar
            </p>
            <form class="user" method="POST" action="{{ route('pasar.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <Label>Kode Pasar:</Label>
                    <input type="text" class="form-control" name="kd_pasar">
                </div>

                <div class="form-group">
                    <Label>Nama Pasar:</Label>
                    <input type="text" class="form-control" name="nama_pasar">
                </div>


                <div class="form-group">
                    <Label>Alamat:</Label>
                    <input type="text" class="form-control" name="alamat">
                </div>

                <div class="form-group">
                    <Label>Jam Buka:</Label>
                    <input type="time" class="form-control" name="jam_buka">
                </div>

                <div class="form-group">
                    <Label>Jam Tutup:</Label>
                    <input type="time" class="form-control" name="jam_tutup">
                </div>

                <button type="submit" class="btn btn-primary mr-2">Submit</button>
            </form>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
