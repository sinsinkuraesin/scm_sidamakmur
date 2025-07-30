<?php

namespace App\Http\Controllers;

use App\Models\Ikan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DataIkanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? Carbon::today()->toDateString();

        $ikans = Ikan::with(['belis', 'detailJual'])->get();

        foreach ($ikans as $ikan) {
            $jumlahMasuk = $ikan->belis()
                ->whereDate('tgl_beli', '<=', $tanggal)
                ->sum('jml_ikan');

            $jumlahKeluar = $ikan->detailJual()
                ->whereHas('jual', function ($q) use ($tanggal) {
                    $q->whereDate('tgl_jual', '<=', $tanggal);
                })
                ->sum('jml_ikan');

            $ikan->stok_per_tanggal = $jumlahMasuk - $jumlahKeluar;
        }

        $i = 0;
        return view('pemilik.data_ikan', compact('ikans', 'i', 'tanggal'));
    }
}
