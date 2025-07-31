<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beli;

class PemilikPembelianController extends Controller
{
    public function index(Request $request)
    {
        $belis = Beli::with(['supplier', 'ikan'])
            ->when($request->input('kata'), function ($query, $kata) {
                $query->where(function ($q) use ($kata) {
                    $q->where('kd_beli', 'like', "%$kata%")
                      ->orWhereHas('supplier', function ($q) use ($kata) {
                          $q->where('nm_supplier', 'like', "%$kata%");
                      })
                      ->orWhereHas('ikan', function ($q) use ($kata) {
                          $q->where('jenis_ikan', 'like', "%$kata%");
                      });
                });
            })
            ->when($request->filled(['dari', 'sampai']), function ($query) use ($request) {
                $query->whereBetween('tgl_beli', [$request->dari, $request->sampai]);
            })
            ->latest()
            ->get();

        $totalPengeluaran = $belis->sum('total_harga');

        return view('pemilik.pembelian', compact('belis', 'totalPengeluaran'));
    }
}
