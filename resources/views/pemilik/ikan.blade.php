@extends('pemilik.layout')
@section('content')

<div class="content-wrapper">
        <div class="row">
            <div class="col-lg-12 stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Data Ikan</h4>
                        <div class="table-responsive pt-3">
                            <table class="display expandable-table table-flush" style="width:100%">
                            <thead class="custom-header">
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Ikan</th>
                                        <th>Harga Beli/Kg</th>
                                        <th>Harga Jual/Kg</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $ikans as $ikan )
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $ikan->jenis_ikan }}</td>
                                        <td>Rp. {{ number_format($ikan->harga_beli, 0, ',', '.') }} /kg</td>
                                        <td>Rp. {{ number_format($ikan->harga_jual, 0, ',', '.') }} /kg</td>
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
    table.display.expandable-table thead.custom-header {
        background-color: #7eb567 !important;
        color: white !important;
    }

    /* Bahkan tambahkan baris ini jika tetap tidak berubah */
    table.display.expandable-table thead.custom-header th {
        background-color: #7eb567 !important;
        color: white !important;
    }
</style>

@endsection
