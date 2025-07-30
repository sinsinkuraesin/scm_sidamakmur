<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beli;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanPembelianController extends Controller
{
    public function index(Request $request)
    {
        $belis = collect(); // Default kosong
        $totalPengeluaran = 0;
        $filter = $request->filter;
        $tanggal = $request->tanggal;

        if ($filter && $tanggal) {
            $belisQuery = Beli::with(['supplier', 'ikan']);

            switch ($filter) {
                case 'bulanan':
                    $belisQuery = $belisQuery->whereMonth('tgl_beli', Carbon::parse($tanggal)->month)
                                            ->whereYear('tgl_beli', Carbon::parse($tanggal)->year);
                    break;

                case 'tahunan':
                    $belisQuery = $belisQuery->whereYear('tgl_beli', Carbon::parse($tanggal)->year);
                    break;

                default: // harian
                    $belisQuery = $belisQuery->whereDate('tgl_beli', $tanggal);
                    break;
            }

            $belis = $belisQuery->latest()->get();
            $totalPengeluaran = $belis->sum('total_harga');
        }

        return view('admin.laporan.pembelian', compact('belis', 'filter', 'tanggal', 'totalPengeluaran'));
    }


    public function cetakPDF(Request $request)
    {
        $filter = $request->filter ?? 'harian';
        $tanggal = $request->tanggal ?? Carbon::today()->format('Y-m-d');

        $belis = Beli::with(['supplier', 'ikan']);

        switch ($filter) {

            case 'bulanan':
                $belis = $belis->whereMonth('tgl_beli', Carbon::parse($tanggal)->month)
                               ->whereYear('tgl_beli', Carbon::parse($tanggal)->year);
                break;

            case 'tahunan':
                $belis = $belis->whereYear('tgl_beli', Carbon::parse($tanggal)->year);
                break;

            default:
                $belis = $belis->whereDate('tgl_beli', $tanggal);
                break;
        }

        $belis = $belis->latest()->get();
        $totalPengeluaran = $belis->sum('total_harga');

        $pdf = Pdf::loadView('admin.laporan.pembelian_pdf', compact('belis', 'filter', 'tanggal', 'totalPengeluaran'));
        return $pdf->stream('laporan-pembelian-' . now()->format('YmdHis') . '.pdf');
    }
}
