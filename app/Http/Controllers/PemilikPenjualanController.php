<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jual;
use App\Models\DetailJual;

class PemilikPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $juals = Jual::with(['konsumen', 'detailJual.ikan'])
            ->when($request->input('kata'), function ($query, $kata) {
                $query->where('kd_jual', 'like', "%$kata%")
                    ->orWhereHas('konsumen', function ($q) use ($kata) {
                        $q->where('nama_konsumen', 'like', "%$kata%");
                    })
                    ->orWhereHas('detailJual.ikan', function ($q) use ($kata) {
                        $q->where('jenis_ikan', 'like', "%$kata%");
                    });
            })
            ->when($request->filled(['dari', 'sampai']), function ($query) use ($request) {
                $query->whereBetween('tgl_jual', [$request->dari, $request->sampai]);
            })
            ->latest()
            ->get();

        $totalPendapatan = $juals->flatMap->detailJual->sum('total');

        return view('pemilik.penjualan', compact('juals', 'totalPendapatan'));
    }
}
