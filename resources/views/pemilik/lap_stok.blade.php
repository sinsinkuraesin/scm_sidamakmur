@extends('pemilik.layout')
@section('content')

<div class="content-wrapper px-2">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Laporan Stok Persediaan</h4>

                    <form method="GET" action="{{ route('pemilik.lap_stok') }}" class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Jenis Ikan</label>
                            <select name="jenis_ikan" class="form-control">
                                <option value="">Semua Jenis</option>
                                @foreach($daftarJenisIkan as $jenis)
                                    <option value="{{ $jenis }}" {{ request('jenis_ikan') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ $tanggal ?? date('Y-m-d') }}">
                        </div>

                        <div class="col-md-4 mt-4 pt-1">
                            <button type="submit" class="btn btn-primary me-2">Tampilkan</button>

                            @if(request()->has('tanggal'))
                                <a href="{{ route('lap_stok.pdf', ['jenis_ikan' => request('jenis_ikan'), 'tanggal' => request('tanggal')]) }}"
                                   target="_blank" class="btn btn-success">Cetak PDF</a>
                            @endif
                        </div>
                    </form>

                    <div class="table-responsive pt-3">
                        <table class="table table-hover table-bordered">
                            <thead class="text-center text-white" style="background: linear-gradient(90deg, #1d976c, #93f9b9);">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Ikan</th>
                                    <th>Stok Awal</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Stok Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @forelse ($laporanStok as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}</td>
                                        <td>{{ $item['jenis_ikan'] }}</td>
                                        <td>{{ $item['stok_awal'] }}Kg</td>
                                        <td>{{ $item['masuk'] }}Kg</td>
                                        <td>{{ $item['keluar'] }}Kg</td>
                                        <td>{{ $item['stok_akhir'] }}Kg</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
