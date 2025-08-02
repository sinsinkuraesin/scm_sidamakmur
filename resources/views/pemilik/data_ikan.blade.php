@extends('pemilik.layout')
@section('content')

<div class="content-wrapper px-2">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Data Ikan</h4>

                    {{-- Filter tanggal stok --}}
                    <form action="{{ route('pemilik.data_ikan') }}" method="GET" class="d-flex align-items-center mb-4">
                        <div class="me-2">
                            <label for="tanggal" class="form-label mb-3 fw-bold">Filter Stok per Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ request('tanggal') }}">
                        </div>
                        <div class="mt-5 pt-1">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-magnify"></i> Tampilkan
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover table-bordered">
                            <thead class="custom-header text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Foto Ikan</th>
                                    <th>Jenis Ikan</th>
                                    <th>Harga Beli/Kg</th>
                                    <th>Harga Jual/Kg</th>
                                    <th>Stok (Kg)</th>
                                </tr>
                            </thead>
                            <tbody class="text-center align-middle">
                                @foreach ($ikans as $i => $ikan)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>
                                        <img src="{{ asset('Foto_Ikan/' . $ikan->foto_ikan) }}" alt="Foto Ikan"
                                             width="140" height="140" style="object-fit: cover; border-radius: 8px;">
                                    </td>
                                    <td>{{ $ikan->jenis_ikan }}</td>
                                    <td>Rp. {{ number_format($ikan->harga_beli, 0, ',', '.') }} /kg</td>
                                    <td>Rp. {{ number_format($ikan->harga_jual, 0, ',', '.') }} /kg</td>
                                    <td>
                                        @if(request('tanggal'))
                                            {{ $ikan->stok_per_tanggal }} kg<br>
                                            <small class="text-muted">per {{ request('tanggal') }}</small>
                                        @else
                                            {{ $ikan->stok }} kg
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    thead.custom-header {
        background: linear-gradient(90deg, #1d976c, #93f9b9);
        color: white;
    }

    table.table-bordered th,
    table.table-bordered td {
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .table th, .table td {
            font-size: 14px;
        }
    }
</style>

@endsection
