@extends('admin.layout')
@section('content')

<div class="col-lg-12 mb-4">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Pembelian</h4>
                        <form class="user" method="POST" action="{{ route('beli.update', $beli->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Kode Pembelian:</label>
                                <input type="text" class="form-control" name="kd_beli" value="{{ $beli->kd_beli }}" required>
                            </div>

                            <!-- Supplier -->
                            <div class="form-group">
                                <label for="kd_supplier">Pilih Supplier:</label>
                                <select name="kd_supplier" id="kd_supplier" class="form-control" required>
                                    <option value="" disabled>Pilih Supplier</option>
                                    @foreach($supplier as $s)
                                        <option value="{{ $s->id }}" {{ $beli->kd_supplier == $s->id ? 'selected' : '' }}>
                                            {{ $s->kd_supplier }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tanggal Pembelian -->
                            <div class="form-group mb-3">
                                <label for="tgl_beli" class="form-label">Tanggal Pembelian:</label>
                                <input type="date" class="form-control" name="tgl_beli" value="{{ $beli->tgl_beli }}" required>
                            </div>

                            <!-- Jenis Ikan -->
                            <div class="form-group mb-3">
                                <label for="jenis_ikan" class="form-label">Jenis Ikan:</label>
                                <input type="text" id="jenis_ikan_display" class="form-control" value="{{ $beli->ikan->jenis_ikan }}" readonly>
                                <input type="hidden" name="jenis_ikan" id="jenis_ikan_hidden" value="{{ $beli->jenis_ikan }}">
                            </div>

                            <!-- Harga Beli -->
                            <div class="form-group mb-3">
                                <label for="harga_beli" class="form-label">Harga/Kg (Rp):</label>
                                <input type="text" id="harga_beli" class="form-control" value="{{ number_format($beli->harga_beli, 0, ',', '.') }}" readonly>
                                <input type="hidden" name="harga_beli" id="harga_beli_hidden" value="{{ $beli->harga_beli }}">
                            </div>

                            <!-- Jumlah -->
                            <div class="form-group mb-3">
                                <label for="jml_ikan" class="form-label">Jumlah Ikan (Kg):</label>
                                <input type="number" class="form-control" name="jml_ikan" id="jml_ikan" value="{{ $beli->jml_ikan }}" required placeholder="Minimal 20 kg">
                            </div>

                            <!-- Total -->
                            <div class="form-group mb-3">
                                <label for="total_harga" class="form-label">Total Harga:</label>
                                <input type="text" id="total_harga" class="form-control" value="{{ number_format($beli->total_harga, 0, ',', '.') }}" readonly>
                                <input type="hidden" name="total_harga" id="total_harga_hidden" value="{{ $beli->total_harga }}">
                            </div>

                            <!-- Bukti Pembayaran -->
                            <div class="form-group mb-3">
                                <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran Baru:</label>
                                <input type="file" class="form-control" name="bukti_pembayaran" accept="image/*">

                                @if ($beli->bukti_pembayaran)
                                    <p class="mt-2">Bukti Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $beli->bukti_pembayaran) }}" alt="Bukti Pembayaran" style="max-height: 200px;">
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('beli.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.error || "Ikan tidak ditemukan",
                    });
                }
            })
            .catch(error => {
                console.error('Gagal fetch:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan mengambil data ikan.',
                });
            });
    });

    function updateTotalHarga() {
        var harga = parseFloat(document.getElementById('harga_beli_hidden').value) || 0;
        var jumlah = parseFloat(document.getElementById('jml_ikan').value) || 0;
        var total = harga * jumlah;

        document.getElementById('total_harga').value = new Intl.NumberFormat('id-ID').format(total);
        document.getElementById('total_harga_hidden').value = total;
    }

    document.getElementById('jml_ikan').addEventListener('input', updateTotalHarga);

    document.querySelector('form.user').addEventListener('submit', function(e) {
        const jumlahIkan = parseFloat(document.getElementById('jml_ikan').value);
        if (jumlahIkan < 20) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Jumlah Ikan Terlalu Sedikit',
                text: 'Pembelian tidak boleh di bawah 20 kg.',
                confirmButtonText: 'OK'
            });
        }
    });
</script>

@endsection
