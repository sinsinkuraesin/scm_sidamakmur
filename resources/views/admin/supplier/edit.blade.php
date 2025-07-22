@extends('admin.layout')
@section('content')
<div class="col-lg-12 mb-4">

    <div class="content-wrapper">
        <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <h4 class="card-title">Update Data Supplier</h4>
                <p class="card-description">
                Masukkan data supplier
                </p>
                <form class="user" method="POST" action="{{ route('supplier.update', $supplier->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')


                    <!-- Kode Supplier -->
                    <div class="form-group">
                        <label for="kd_supplier">Kode Supplier:</label>
                        <input type="text" class="form-control" name="kd_supplier" id="kd_supplier" value="{{ $supplier->kd_supplier }}" required>
                    </div>

                    <!-- Nama Supplier -->
                    <div class="form-group">
                        <label for="kd_supplier">Nama Supplier:</label>
                        <input type="text" class="form-control" name="nm_supplier" id="nm_supplier" value="{{ $supplier->nm_supplier }}" required>
                    </div>

                    <!-- Pilih jenis ikan -->
                    <div class="form-group">
                        <label for="jenis_ikan">Pilih Jenis Ikan:</label>
                        <select name="jenis_ikan" id="jenis_ikan" class="form-control" required>
                            <option value="" disabled>Pilih Jenis Ikan</option>
                            @foreach ($ikan as $i)
                            <option value="{{ $i->id }}" {{ $i->id == $supplier->jenis_ikan ? 'selected' : '' }}>
                                {{ $i->jenis_ikan }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Alamat -->
                    <div class="form-group">
                        <label for="total_harga">Alamat:</label>
                        <input type="text" class="form-control" name="alamat" id="alamat" value="{{ $supplier->alamat }}" required>
                    </div>

                    <!-- Submit Button -->
                    <center><button type="submit" class="btn btn-primary">Simpan Perubahan</button></center>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
    @endsection


