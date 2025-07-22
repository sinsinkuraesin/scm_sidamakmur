@extends('admin.layout')
@section('content')
<div class="col-lg-12 mb-4">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Update Data Konsumen</h4>
                        <p class="card-description">Masukkan data konsumen</p>

                        <form class="user" method="POST" action="{{ route('konsumen.update', $konsumen->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="kd_konsumen">Kode konsumen:</label>
                                <input type="text" class="form-control" name="kd_konsumen" id="kd_konsumen" value="{{ $konsumen->kd_konsumen }}" required>
                            </div>

                            <!-- Nama Konsumen -->
                            <div class="form-group">
                                <label for="nama_konsumen">Nama konsumen:</label>
                                <input type="text" class="form-control" name="nama_konsumen" id="nama_konsumen" value="{{ $konsumen->nama_konsumen }}" required>
                            </div>


                            <div class="form-group">
                                <label for="no_tlp">No Telephone:</label>
                                <input type="text" class="form-control" name="no_tlp" id="no_tlp" value="{{ $konsumen->no_tlp }}" required>
                            </div>

                            <!-- Pilih Pasar -->
                            <div class="form-group">
                                <label for="nama_pasar">Pilih Pasar:</label>
                                <select name="nama_pasar" id="nama_pasar" class="form-control" required>
                                    <option value="" disabled>Pilih Pasar</option>
                                    @foreach ($pasar as $i)
                                        <option value="{{ $i->id }}" {{ $i->id == $konsumen->nama_pasar ? 'selected' : '' }}>
                                            {{ $i->nama_pasar }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Alamat -->
                            <div class="form-group">
                                <label for="alamat">Alamat:</label>
                                <input type="text" id="alamat" class="form-control" value="{{ $konsumen->alamat }}" readonly>
                                <input type="hidden" name="alamat" id="alamat_hidden" value="{{ $konsumen->alamat }}">
                            </div>

                            <!-- Jam Buka -->
                            <div class="form-group">
                                <label for="jam_buka">Jam Buka:</label>
                                <input type="text" id="jam_buka" class="form-control" value="{{ $konsumen->jam_buka }}" readonly>
                                <input type="hidden" name="jam_buka" id="jam_buka_hidden" value="{{ $konsumen->jam_buka }}">
                            </div>

                            <!-- Jam Tutup -->
                            <div class="form-group">
                                <label for="jam_tutup">Jam Tutup:</label>
                                <input type="text" id="jam_tutup" class="form-control" value="{{ $konsumen->jam_tutup }}" readonly>
                                <input type="hidden" name="jam_tutup" id="jam_tutup_hidden" value="{{ $konsumen->jam_tutup }}">
                            </div>

                            <!-- Submit -->
                            <center>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        const pasarData = @json($pasar);

        // Jalankan saat user mengganti pasar
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

        // Jalankan saat halaman pertama kali dimuat
        window.addEventListener('DOMContentLoaded', function () {
            const selectedId = document.getElementById('nama_pasar').value;
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
