<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jual;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PemilikLaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $penjualans = collect();
        $total = 0;
        $total_kg = 0;
        $filter = $request->get('filter', 'hari');
        $tanggal = $request->get('tanggal', now()->toDateString());

        if ($filter && $tanggal) {
            $filter = in_array($filter, ['hari', 'bulan', 'tahun']) ? $filter : 'hari';

            $query = Jual::with(['detailJual.ikan', 'konsumen']);

            switch ($filter) {
                case 'bulan':
                    $query->whereMonth('tgl_jual', Carbon::parse($tanggal)->month)
                          ->whereYear('tgl_jual', Carbon::parse($tanggal)->year);
                    break;
                case 'tahun':
                    $query->whereYear('tgl_jual', Carbon::parse($tanggal)->year);
                    break;
                default:
                    $query->whereDate('tgl_jual', $tanggal);
                    break;
            }

            $penjualans = $query->latest()->get();
            $total = $penjualans->sum(fn($jual) => $jual->detailJual->sum('total'));
            $total_kg = $penjualans->sum(fn($jual) => $jual->detailJual->sum('jml_ikan'));
        }

        return view('pemilik.lap_penjualan', compact('penjualans', 'total', 'total_kg', 'filter', 'tanggal'));
    }

    public function cetakPDF(Request $request)
    {
        $filter = $request->get('filter', 'hari');
        $tanggal = $request->get('tanggal', now()->toDateString());

        $filter = in_array($filter, ['hari', 'bulan', 'tahun']) ? $filter : 'hari';

        $penjualans = Jual::with(['detailJual.ikan', 'konsumen']);

        switch ($filter) {
            case 'bulan':
                $penjualans->whereMonth('tgl_jual', Carbon::parse($tanggal)->month)
                           ->whereYear('tgl_jual', Carbon::parse($tanggal)->year);
                break;
            case 'tahun':
                $penjualans->whereYear('tgl_jual', Carbon::parse($tanggal)->year);
                break;
            default:
                $penjualans->whereDate('tgl_jual', $tanggal);
                break;
        }

        $penjualans = $penjualans->latest()->get();

        $total = $penjualans->sum(fn($jual) => $jual->detailJual->sum('total'));
        $total_kg = $penjualans->sum(fn($jual) => $jual->detailJual->sum('jml_ikan'));

        $pdf = Pdf::loadView('admin.laporan.penjualan_pdf', compact(
            'penjualans', 'total', 'total_kg', 'filter', 'tanggal'
        ));

        return $pdf->stream('laporan-penjualan-pemilik-' . now()->format('YmdHis') . '.pdf');
    }
}
