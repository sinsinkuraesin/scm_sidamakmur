@extends('admin.layout')
@section('content')
<div class="col-lg-12 mb-4">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Penjualan</h4>
                        <p class="card-description">Ubah data penjualan</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $err)
                                        <li>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('jual.update', $jual->jual_id) }}">
                            @csrf
                            @method('PUT')

                            <!-- Konsumen -->
                            <div class="form-group mb-3">
                                <label for="nama_konsumen">Pilih Konsumen:</label>
                                <select name="nama_konsumen" id="nama_konsumen" class="form-control" required>
                                    <option value="" disabled>Pilih Konsumen</option>
                                    @foreach ($konsumen as $k)
                                        <option value="{{ $k->id }}" data-pasar="{{ $k->nama_pasar_asli }}"
                                            {{ $k->id == $jual->nama_konsumen ? 'selected' : '' }}>
                                            {{ $k->nama_konsumen }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Tanggal -->
                            <div class="form-group mb-3">
                                <label for="tgl_jual">Tanggal Penjualan:</label>
                                <input type="date" class="form-control" name="tgl_jual" value="{{ $jual->tgl_jual }}" required>
                            </div>

                            <!-- Pasar -->
                            <div class="form-group mb-3">
                                <label for="nama_pasar">Nama Pasar:</label>
                                <input type="text" id="nama_pasar" class="form-control" readonly value="{{ old('nama_pasar', $jual->nama_pasar) }}">
                                <input type="hidden" name="nama_pasar" id="nama_pasar_hidden" value="{{ old('nama_pasar', $jual->nama_pasar) }}">
                            </div>

                            <!-- Judul Kolom -->
                            <div class="form-group">
                                <label class="form-label">Pilih Ikan:</label>
                                <div class="row font-weight-bold mb-2 px-2">
                                    <div class="col-md-1 text-center">✔</div>
                                    <div class="col-md-3">Jenis Ikan</div>
                                    <div class="col-md-2">Harga Jual</div>
                                    <div class="col-md-3">Jumlah (Stok)</div>
                                    <div class="col-md-3">Total</div>
                                </div>
                            </div>

                            <!-- Daftar Ikan -->
                            @foreach ($ikan as $i)
                            @php
                                $detail = $jual->detailJual->where('jenis_ikan', $i->id)->first();
                                $stokKosong = $i->stok <= 0;
                            @endphp
                            <div class="border rounded p-3 mb-2 bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <input type="checkbox"
                                            name="ikan[{{ $i->id }}][checked]"
                                            value="1"
                                            class="form-check-input ikan-checkbox"
                                            {{ $detail ? 'checked' : '' }}
                                            {{ $stokKosong && !$detail ? 'disabled' : '' }}>
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
                                            value="{{ $detail ? $detail->jml_ikan : '' }}"
                                            placeholder="{{ $stokKosong ? 'Stok kosong' : 'Stok tersedia: ' . $i->stok }}"
                                            {{ $detail ? '' : 'disabled' }}>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control total-display" readonly
                                            value="{{ $detail ? number_format($detail->total, 0, ',', '.') : '' }}">
                                        <input type="hidden" class="total-hidden" name="ikan[{{ $i->id }}][total]" value="{{ $detail ? $detail->total : 0 }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Total -->
                            <div class="form-group mt-4">
                                <label for="total">Total Penjualan (Rp):</label>
                                <input type="text" id="total" class="form-control" readonly value="{{ number_format($jual->detailJual->sum('total'), 0, ',', '.') }}">
                                <input type="hidden" name="total" id="total_hidden" value="{{ $jual->detailJual->sum('total') }}">
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Simpan Perubahan</button>
                            <a href="{{ route('jual.index') }}" class="btn btn-secondary mt-2">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT -->
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
            const total = harga * jumlah;

            wrapper.querySelector('.total-display').value = new Intl.NumberFormat('id-ID').format(total);
            wrapper.querySelector('.total-hidden').value = total;
            updateGrandTotal();
        });
    });

    window.addEventListener('DOMContentLoaded', updateGrandTotal);
</script>

<!-- STYLE -->
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
