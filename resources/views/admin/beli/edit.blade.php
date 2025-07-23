@extends('admin.layout')
@section('content')

<div class="col-lg-12 mb-4">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Pembelian</h4>
                        <form method="POST" action="{{ route('beli.update', $beli->id) }}">
                            @csrf
                            @method('PUT')

                            <!-- Supplier -->
                            <div class="form-group">
                                <label>Supplier</label>
                                <select name="kd_supplier" id="supplier" class="form-control" required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach($supplier as $s)
                                        <option value="{{ $s->id }}" {{ $beli->kd_supplier == $s->id ? 'selected' : '' }}>
                                            {{ $s->kd_supplier }}{{ $s->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tanggal Pembelian -->
                            <div class="form-group">
                                <label>Tanggal Beli</label>
                                <input type="date" name="tgl_beli" class="form-control" value="{{ $beli->tgl_beli }}" required>
                            </div>

                            <!-- Jenis Ikan -->
                            <div class="form-group">
                                <label>Jenis Ikan</label>
                                <input type="text" id="jenis_ikan_display" class="form-control" value="{{ $beli->ikan->jenis_ikan }}" readonly>
                                <input type="hidden" name="jenis_ikan" id="jenis_ikan_hidden" value="{{ $beli->jenis_ikan }}">
                            </div>

                            <!-- Harga Beli -->
                            <div class="form-group mb-3">
                                <label for="harga" class="form-label">Harga/Kg (Rp):</label>
                                <input type="number" name="harga_beli" id="harga" class="form-control" value="{{ $beli->harga_beli }}" readonly>
                            </div>

                            <!-- Jumlah -->
                            <div class="form-group">
                                <label>Jumlah Ikan</label>
                                <input type="number" name="jml_ikan" class="form-control" value="{{ $beli->jml_ikan }}" required>
                            </div>

                            <!-- Total -->
                            <div class="form-group">
                                <label>Total Harga</label>
                                <input type="number" name="total_harga" class="form-control" value="{{ $beli->total_harga }}" readonly>
                            </div>

                            <div class="form-group mb-3">
                                <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran Baru:</label>
                                <input type="file" class="form-control" name="bukti_pembayaran" accept="image/*">

                                @if ($beli->bukti_pembayaran)
                                    <p class="mt-2">Bukti Saat Ini:</p>
                                    <img src="{{ asset('storage/' . $beli->bukti_pembayaran) }}" alt="Bukti Pembayaran" style="max-height: 200px;">
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('beli.index') }}" class="btn btn-light">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJAX: Update Jenis Ikan & Harga -->
<script>
document.getElementById('supplier').addEventListener('change', function () {
    const supplierId = this.value;

    fetch(`/get-ikan-supplier/${supplierId}`)
        .then(res => res.json())
        .then(data => {
            if (data && !data.error) {
                document.getElementById('jenis_ikan_display').value = data.jenis_ikan;
                document.getElementById('jenis_ikan_hidden').value = data.id;
                document.getElementById('harga').value = data.harga_beli;
                hitungTotal();
            } else {
                alert(data.error || "Ikan tidak ditemukan");
            }
        })
        .catch(error => {
            console.error('Gagal fetch:', error);
            alert('Terjadi kesalahan mengambil data ikan.');
        });
});

function hitungTotal() {
    const harga = parseFloat(document.getElementById('harga').value) || 0;
    const jumlah = parseFloat(document.querySelector('input[name="jml_ikan"]').value) || 0;
    document.querySelector('input[name="total_harga"]').value = harga * jumlah;
}

document.getElementById('harga').addEventListener('input', hitungTotal);
document.querySelector('input[name="jml_ikan"]').addEventListener('input', hitungTotal);
</script>

@endsection
