@extends('admin.layout')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12 stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Laporan Persediaan PD. Sidamakmur</h4>

                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('laporan.persediaan') }}" class="form-inline mb-4">
                        <div class="form-group mr-3">
                            <label class="mr-2">Jenis Ikan</label>
                            <select name="jenis_ikan" class="form-control">
                                <option value="">Semua Jenis Ikan</option>
                                @foreach($daftarJenisIkan as $jenis)
                                    <option value="{{ $jenis }}" {{ request('jenis_ikan') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mr-3">
                            <label class="mr-2">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') ?? date('Y-m-d') }}">
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">Tampilkan</button>

                        @if(request()->has('tanggal'))
                        <a href="{{ route('laporan.persediaan.pdf', ['jenis_ikan' => request('jenis_ikan'), 'tanggal' => request('tanggal')]) }}"
                            target="_blank" class="btn btn-success">Cetak PDF</a>
                        @endif
                    </form>

                    <div class="table-responsive pt-3">
                            <table class="display expandable-table table-flush" style="width:100%">
                                <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Ikan</th>
                                    <th>Stok Awal (Kg)</th>
                                    <th>Masuk (Kg)</th>
                                    <th>Keluar (Kg)</th>
                                    <th>Stok Akhir (Kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($laporanStok as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item['tanggal'] }}</td>
                                        <td>{{ $item['jenis_ikan'] }}</td>
                                        <td>{{ $item['stok_awal'] }}Kg</td>
                                        <td>{{ $item['masuk'] }}Kg</td>
                                        <td>{{ $item['keluar'] }}Kg</td>
                                        <td>{{ $item['stok_akhir'] }}Kg</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data persediaan</td>
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
