@extends('admin.layout')
@section('content')
<div class="col-lg-12 mb-4">

    <div class="content-wrapper">
        <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Pembelian</h4>
                <p class="card-description">
                Masukkan data pembelian
                </p>
                <form class="user" method="POST" action="{{ route('beli.store') }}" enctype="multipart/form-data">
                    @csrf

                        <div class="form-group">
                            <label for="kd_supplier">Pilih Supplier:</label>
                            <select name="kd_supplier" id="kd_supplier" class="form-control" required>
                            <option value="" disabled selected>Pilih Supplier</option>
                            @foreach ($supplier as $item)
                                <option value="{{ $item->id }}">{{ $item->kd_supplier }}</option>
                            @endforeach
                        </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="tgl_beli" class="form-label">Tanggal Pembelian:</label>
                            <input type="date" class="form-control" name="tgl_beli" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jenis_ikan" class="form-label">Jenis Ikan:</label>
                            <input type="text" id="jenis_ikan_display" class="form-control" readonly>
                            <input type="hidden" name="jenis_ikan" id="jenis_ikan_hidden">
                        </div>


                        <div class="form-group mb-3">
                            <label for="harga" class="form-label">Harga/Kg (Rp):</label>
                            <input type="text" id="harga_beli" class="form-control" readonly>
                            <input type="hidden" name="harga_beli" id="harga_beli_hidden">
                        </div>

                        <div class="form-group mb-3">
                            <label for="jml_ikan" class="form-label">Jumlah Ikan:</label>
                            <input type="number" class="form-control" name="jml_ikan" id="jml_ikan" min="20" required placeholder="Minimal 20 kg">
                        </div>

                        <div class="form-group mb-3">
                            <label for="total_harga" class="form-label">Total Harga:</label>
                            <input type="text" id="total_harga" class="form-control" readonly>
                            <input type="hidden" name="total_harga" id="total_harga_hidden">
                        </div>

                        <div class="form-group mb-3">
                            <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran:</label>
                            <input type="file" class="form-control" name="bukti_pembayaran" accept="image/*">
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('beli.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div>

<script>
    document.getElementById('kd_supplier').addEventListener('change', function () {
        const supplierId = this.value;

        fetch(`/get-ikan-supplier/${supplierId}`)
            .then(res => res.json())
            .then(data => {
                if (data && !data.error) {
                    document.getElementById('jenis_ikan_display').value = data.jenis_ikan;
                    document.getElementById('jenis_ikan_hidden').value = data.id;

                    document.getElementById('harga_beli').value = new Intl.NumberFormat('id-ID').format(data.harga_beli);
                    document.getElementById('harga_beli_hidden').value = data.harga_beli;

                    updateTotalHarga();
                } else {
                    alert(data.error || "Ikan tidak ditemukan");
                }
            })
            .catch(error => {
                console.error('Gagal fetch:', error);
                alert('Terjadi kesalahan mengambil data ikan.');
            });
    });

    function updateTotalHarga() {
        var harga = parseFloat(document.getElementById('harga_beli_hidden').value) || 0;
        var jumlah = parseFloat(document.getElementById('jml_ikan').value) || 0;

        var totalHarga = harga * jumlah;

        document.getElementById('total_harga').value = new Intl.NumberFormat('id-ID').format(totalHarga);
        document.getElementById('total_harga_hidden').value = totalHarga;
    }

    document.getElementById('jml_ikan').addEventListener('input', updateTotalHarga);
</script>

@endsection
