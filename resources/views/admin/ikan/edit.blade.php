@extends('admin.layout')
@section('content')

<div class="content-wrapper">
    <div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
        <div class="card-body">
            <h4 class="card-title">Update Data Ikan</h4>
            <p class="card-description">
            Update data ikan
            </p>
            <form class="user" method="POST" action="{{ route('ikan.update', $ikan->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <Label>Kode Ikan:</Label>
                    <input type="text" class="form-control" name="kd_ikan" value="{{ $ikan->kd_ikan }}">
                </div>

                <div class="form-group">
                    <Label>Jenis Ikan:</Label>
                    <input type="text" class="form-control" name="jenis_ikan" value="{{ $ikan->jenis_ikan }}">
                </div>

                <div class="form-group">
                    <Label>Harga Beli/kg :</Label><br>
                    <input type="text" class="form-control" name="harga_beli" value="{{ $ikan->harga_beli }}">
                </div>

                <div class="form-group">
                        <Label>Harga Jual/kg :</Label>
                        <input type="text" class="form-control" name="harga_jual" value="{{ $ikan->harga_jual }}">
                </div>

                <div class="form-group">
                    <label>Stok (Kg):</label>
                    <input type="number" class="form-control" name="stok" value="{{ $ikan->stok }}">
                </div>

                <button type="submit" class="btn btn-primary mr-2">Submit</button>
            </form>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
