@extends('admin.layout')
@section('content')
<div class="col-lg-12 mb-4">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Tambah Penjualan</h4>
                        <p class="card-description">Masukkan data penjualan</p>

                        {{-- Tampilkan pesan error --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('jual.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="kd_jual">Kode Jual:</label>
                                <input type="text" name="kd_jual" id="kd_jual" class="form-control" required>
                            </div>


                            <div class="form-group mb-3">
                                <label for="nama_konsumen">Pilih Nama Konsumen:</label>
                                <select name="nama_konsumen" id="nama_konsumen" class="form-control @error('nama_konsumen') is-invalid @enderror" required>
                                    <option value="" disabled selected>Pilih Nama Konsumen</option>
                                    @foreach ($konsumen as $k)
                                        <option value="{{ $k->id }}" data-pasar="{{ $k->nama_pasar_asli }}">
                                            {{ $k->nama_konsumen }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nama_konsumen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="tgl_jual">Tanggal Penjualan:</label>
                                <input type="date" class="form-control @error('tgl_jual') is-invalid @enderror" name="tgl_jual" required>
                                @error('tgl_jual') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label for="nama_pasar">Nama Pasar:</label>
                                <input type="text" id="nama_pasar" class="form-control" readonly>
                                <input type="hidden" name="nama_pasar" id="nama_pasar_hidden">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Pilih Ikan:</label>
                                <div class="row font-weight-bold mb-2 px-2">
                                    <div class="col-md-1 text-center">✔</div>
                                    <div class="col-md-3">Jenis Ikan</div>
                                    <div class="col-md-2">Harga Jual</div>
                                    <div class="col-md-3">Jumlah (Stok)/Kg</div>
                                    <div class="col-md-3">Total</div>
                                </div>
                            </div>

                            @foreach ($ikan as $i)
                            @php $stokKosong = $i->stok <= 0; @endphp
                            <div class="border rounded p-3 mb-2 bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <input type="checkbox"
                                            name="ikan[{{ $i->id }}][checked]"
                                            value="1"
                                            class="form-check-input ikan-checkbox"
                                            {{ $stokKosong ? 'disabled' : '' }}>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>{{ $i->jenis_ikan }}</strong>
                                        <input type="hidden" name="ikan[{{ $i->id }}][id]" value="{{ $i->id }}">
                                    </div>
                                    <div class="col-md-2">
                                        Rp {{ number_format($i->harga_jual, 0, ',', '.') }}
                                        <input type="hidden" class="harga" value="{{ $i->harga_jual }}">
                                        <input type="hidden" name="ikan[{{ $i->id }}][harga]" value="{{ $i->harga_jual }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number"
                                            name="ikan[{{ $i->id }}][jumlah]"
                                            class="form-control jumlah"
                                            min="1"
                                            max="{{ $i->stok }}"
                                            placeholder="{{ $stokKosong ? 'Stok kosong': 'Stok tersedia: ' . $i->stok }}"
                                            disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control total-display" readonly placeholder="Rp 0">
                                        <input type="hidden" class="total-hidden" name="ikan[{{ $i->id }}][total]" value="0">
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <div class="form-group mb-3">
                                <label for="status">Status Penjualan:</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="Diproses" selected>Diproses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>


                            <div class="form-group mt-4">
                                <label for="total">Total Penjualan (Rp):</label>
                                <input type="text" id="total" class="form-control" readonly>
                                <input type="hidden" name="total" id="total_hidden">
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Simpan</button>
                            <a href="{{ route('jual.index') }}" class="btn btn-secondary mt-2">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('nama_konsumen').addEventListener('change', function () {
        const pasar = this.options[this.selectedIndex].getAttribute('data-pasar');
        document.getElementById('nama_pasar').value = pasar;
        document.getElementById('nama_pasar_hidden').value = pasar;
    });

    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.total-hidden').forEach(input => {
            const checkbox = input.closest('.border').querySelector('.ikan-checkbox');
            if (checkbox && checkbox.checked) {
                const total = parseFloat(input.value) || 0;
                grandTotal += total;
            }
        });

        document.getElementById('total').value = new Intl.NumberFormat('id-ID').format(grandTotal);
        document.getElementById('total_hidden').value = grandTotal;
    }

    document.querySelectorAll('.ikan-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const wrapper = this.closest('.border');
            const jumlahInput = wrapper.querySelector('.jumlah');
            const totalDisplay = wrapper.querySelector('.total-display');
            const totalHidden = wrapper.querySelector('.total-hidden');

            if (this.checked) {
                jumlahInput.removeAttribute('disabled');
                jumlahInput.focus();
            } else {
                jumlahInput.setAttribute('disabled', true);
                jumlahInput.value = '';
                totalDisplay.value = '';
                totalHidden.value = 0;
                updateGrandTotal();
            }
        });
    });

    document.querySelectorAll('.jumlah').forEach(jumlahInput => {
        jumlahInput.addEventListener('input', function () {
            const wrapper = this.closest('.border');
            const harga = parseFloat(wrapper.querySelector('.harga').value) || 0;
            const jumlah = parseFloat(this.value) || 0;
            const maxStok = parseFloat(this.getAttribute('max'));

            if (jumlah > maxStok) {
                this.value = maxStok;

                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Tidak Cukup!',
                    html: 'Jumlah melebihi stok tersedia. Maksimal: <b>' + maxStok + '</b> ekor.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Oke',
                });
            }

            const total = harga * this.value;
            wrapper.querySelector('.total-display').value = new Intl.NumberFormat('id-ID').format(total);
            wrapper.querySelector('.total-hidden').value = total;
            updateGrandTotal();
        });
    });
</script>

<style>
    .ikan-checkbox {
        transform: scale(1.4);
        margin-top: 4px;
    }

    .form-control:disabled {
        background-color: #f2f2f2;
    }

    .total-display {
        background-color: #f9f9f9;
    }

    .border.bg-light:hover {
        background-color: #eef6ff;
    }

    .card-title {
        font-weight: bold;
    }
</style>
@endsection
