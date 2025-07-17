<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jual;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter') ?? 'hari';
        $tanggal = $request->get('tanggal') ?? now()->toDateString();

       $penjualans = Jual::with(['detailJual.ikan', 'konsumen']);

        switch ($filter) {
            case 'hari':
                $penjualans->whereDate('tgl_jual', $tanggal);
                break;
            case 'bulan':
                $penjualans->whereMonth('tgl_jual', \Carbon\Carbon::parse($tanggal)->month)
                        ->whereYear('tgl_jual', \Carbon\Carbon::parse($tanggal)->year);
                break;
            case 'tahun':
                $penjualans->whereYear('tgl_jual', \Carbon\Carbon::parse($tanggal)->year);
                break;
        }

        $penjualans = $penjualans->latest()->get();
        $total = $penjualans->sum(fn($jual) => $jual->detailJual->sum('total'));
        $total_kg = $penjualans->sum(fn($jual) => $jual->detailJual->sum('jumlah'));

        return view('admin.laporan.penjualan', compact('penjualans', 'total', 'filter', 'tanggal'));
    }

    public function cetak(Request $request)
    {
        $filter = $request->get('filter') ?? 'hari';
        $tanggal = $request->get('tanggal') ?? now()->toDateString();

        $penjualans = Jual::with(['detailJual.ikan', 'konsumen']);

        switch ($filter) {
            case 'hari':
                $penjualans->whereDate('tgl_jual', $tanggal);
                break;
            case 'bulan':
                $penjualans->whereMonth('tgl_jual', \Carbon\Carbon::parse($tanggal)->month)
                        ->whereYear('tgl_jual', \Carbon\Carbon::parse($tanggal)->year);
                break;
            case 'tahun':
                $penjualans->whereYear('tgl_jual', \Carbon\Carbon::parse($tanggal)->year);
                break;
        }

        $penjualans = $penjualans->latest()->get();

        $total = $penjualans->sum(fn($jual) => $jual->detailJual->sum('total'));
        $total_kg = $penjualans->sum(fn($jual) => $jual->detailJual->sum('jml_ikan'));

        $pdf = Pdf::loadView('admin.laporan.penjualan_pdf', compact(
            'penjualans', 'total', 'total_kg', 'filter', 'tanggal' // <-- tambahkan tanggal
        ));

        return $pdf->stream('laporan-penjualan.pdf');

        }
}
