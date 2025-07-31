<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beli;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PemilikLaporanPembelianController extends Controller
{
    // Menampilkan halaman laporan pembelian untuk pemilik
    public function index(Request $request)
    {
        $belis = collect();
        $totalPengeluaran = 0;
        $filter = $request->filter ?? 'harian';
        $tanggal = $request->tanggal ?? now()->toDateString();

        if ($filter && $tanggal) {
            $query = Beli::with(['supplier', 'ikan']);

            switch ($filter) {
                case 'bulanan':
                    $query->whereMonth('tgl_beli', Carbon::parse($tanggal)->month)
                          ->whereYear('tgl_beli', Carbon::parse($tanggal)->year);
                    break;

                case 'tahunan':
                    $query->whereYear('tgl_beli', Carbon::parse($tanggal)->year);
                    break;

                default: // harian
                    $query->whereDate('tgl_beli', $tanggal);
                    break;
            }

            $belis = $query->latest()->get();
            $totalPengeluaran = $belis->sum('total_harga');
        }

        return view('pemilik.lap_pembelian', compact('belis', 'filter', 'tanggal', 'totalPengeluaran'));
    }

    // Cetak PDF laporan pembelian untuk pemilik
    public function cetakPDF(Request $request)
    {
        $filter = $request->filter ?? 'harian';
        $tanggal = $request->tanggal ?? now()->toDateString();

        $query = Beli::with(['supplier', 'ikan']);

        switch ($filter) {
            case 'bulanan':
                $query->whereMonth('tgl_beli', Carbon::parse($tanggal)->month)
                      ->whereYear('tgl_beli', Carbon::parse($tanggal)->year);
                break;
            case 'tahunan':
                $query->whereYear('tgl_beli', Carbon::parse($tanggal)->year);
                break;
            default:
                $query->whereDate('tgl_beli', $tanggal);
                break;
        }

        $belis = $query->latest()->get();
        $totalPengeluaran = $belis->sum('total_harga');

        $pdf = Pdf::loadView('admin.laporan.pembelian_pdf', compact('belis', 'filter', 'tanggal', 'totalPengeluaran'));
        return $pdf->stream('laporan-pembelian-pemilik-' . now()->format('YmdHis') . '.pdf');
    }
}
