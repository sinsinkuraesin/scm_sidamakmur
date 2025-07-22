@extends('admin.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Update Data Pasar</h4>
            <p class="card-description">
            Update data pasar
            </p>
            <form class="user" method="POST" action="{{ route('pasar.update', $pasar->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <Label>Kode Pasar:</Label>
                    <input type="text" class="form-control" name="kd_pasar" value="{{ $pasar->kd_pasar }}">
                </div>

                <div class="form-group">
                    <Label>Nama Pasar:</Label>
                    <input type="text" class="form-control" name="nama_pasar" value="{{ $pasar->nama_pasar }}">
                </div>

                <div class="form-group">
                    <Label>Alamat:</Label><br>
                    <input type="text" class="form-control" name="alamat" value="{{ $pasar->alamat }}">
                </div>

                <div class="form-group">
                    <Label>Jam Buka:</Label>
                    <input type="time" class="form-control" name="jam_buka" value="{{ $pasar->jam_buka }}" required>
                </div>

                <div class="form-group">
                    <Label>Jam Tutup:</Label>
                    <input type="time" class="form-control" name="jam_tutup" value="{{ $pasar->jam_tutup }}" required>
                </div>

                <button type="submit" class="btn btn-primary mr-2">Submit</button>
            </form>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
