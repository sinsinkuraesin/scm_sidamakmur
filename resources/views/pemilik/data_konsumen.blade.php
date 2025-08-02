@extends('pemilik.layout')
@section('content')

<div class="content-wrapper px-2">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Data Konsumen</h4>

                    {{-- Form Pencarian --}}
                    <form action="{{ route('pemilik.data_konsumen') }}" method="GET" class="d-flex align-items-center mb-4">
                        <div class="me-2">
                            <label for="kata" class="form-label mb-3 fw-bold">Cari Konsumen</label>
                            <input type="text" name="kata" id="kata" class="form-control" value="{{ request('kata') }}" placeholder="Cari nama, kode, pasar..." required>
                        </div>
                        <div class="mt-5 pt-1">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-magnify"></i> Cari
                            </button>
                        </div>
                    </form>

                    {{-- Notifikasi --}}
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    {{-- Tabel Data --}}
                    <div class="table-responsive mt-4">
                        <table class="table table-hover table-bordered">
                            <thead class="custom-header text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Konsumen</th>
                                    <th>Nama Konsumen</th>
                                    <th>No Telepon</th>
                                    <th>Nama Pasar</th>
                                    <th>Alamat</th>
                                    <th>Jam Operasional</th>
                                </tr>
                            </thead>
                            <tbody class="text-center align-middle">
                                @forelse ($konsumens as $i => $konsumen)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $konsumen->kd_konsumen }}</td>
                                        <td>{{ $konsumen->nama_konsumen }}</td>
                                        <td class="text-justify-cell">{{ $konsumen->no_tlp }}</td>
                                        <td class="text-justify-cell">{{ $konsumen->nama_pasar }}</td>
                                        <td class="text-justify-cell">{{ $konsumen->alamat }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $konsumen->jam_buka)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $konsumen->jam_tutup)->format('H:i') }} WIB
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">Data tidak ditemukan.</td>
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
