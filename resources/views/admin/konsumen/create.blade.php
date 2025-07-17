@extends('admin.layout')
@section('content')
<div class="col-lg-12 mb-4">
    <div class="content-wrapper">
        <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Data Konsumen</h4>
                <p class="card-description">Masukkan data Konsumen</p>
                <form class="user" method="POST" action="{{ route('konsumen.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <Label>Nama Konsumen:</Label>
                        <input type="text" class="form-control" name="nama_konsumen">
                    </div>

                    <div class="form-group">
                        <label for="nama_pasar">Pilih Pasar:</label>
                        <select name="nama_pasar" id="nama_pasar" class="form-control" required>
                            <option value="" disabled selected>Pilih Pasar</option>
                            @foreach ($pasar as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_pasar }}</option>
                            @endforeach
                        </select>
                    </div>

                     <div class="form-group">
                        <label for="alamat">Alamat:</label>
                        <input type="text" id="alamat" class="form-control" readonly>
                        <input type="hidden" name="alamat" id="alamat_hidden">
                    </div>

                    <div class="form-group">
                        <label for="jam_buka">Jam Buka:</label>
                        <input type="text" id="jam_buka" class="form-control" readonly>
                        <input type="hidden" name="jam_buka" id="jam_buka_hidden">
                    </div>

                    <div class="form-group">
                        <label for="jam_tutup">Jam tutup:</label>
                        <input type="text" id="jam_tutup" class="form-control" readonly>
                        <input type="hidden" name="jam_tutup" id="jam_tutup_hidden">
                    </div>

                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                </form>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
    const pasarData = @json($pasar);

    document.getElementById('nama_pasar').addEventListener('change', function () {
        const selectedId = this.value;
        const selectedPasar = pasarData.find(p => p.id == selectedId);
        if (selectedPasar) {
            document.getElementById('alamat').value = selectedPasar.alamat;
            document.getElementById('alamat_hidden').value = selectedPasar.alamat;
            document.getElementById('jam_buka').value = selectedPasar.jam_buka;
            document.getElementById('jam_buka_hidden').value = selectedPasar.jam_buka;
            document.getElementById('jam_tutup').value = selectedPasar.jam_tutup;
            document.getElementById('jam_tutup_hidden').value = selectedPasar.jam_tutup;
        }
    });
</script>


@endsection
