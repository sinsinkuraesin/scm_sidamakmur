<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemilikKonsumenController extends Controller
{
    public function index(Request $request)
    {
        $kata = $request->input('kata');

        $query = DB::table('tbl_konsumen')
            ->join('tbl_pasar', 'tbl_konsumen.nama_pasar', '=', 'tbl_pasar.id')
            ->select(
                'tbl_konsumen.*',
                'tbl_pasar.nama_pasar as nama_pasar',
                'tbl_pasar.alamat as alamat',
                'tbl_pasar.jam_buka',
                'tbl_pasar.jam_tutup'
            );

        if ($kata) {
            $query->where(function ($q) use ($kata) {
                $q->where('tbl_konsumen.kd_konsumen', 'LIKE', "%$kata%")
                  ->orWhere('tbl_konsumen.nama_konsumen', 'LIKE', "%$kata%")
                  ->orWhere('tbl_konsumen.no_tlp', 'LIKE', "%$kata%")
                  ->orWhere('tbl_pasar.nama_pasar', 'LIKE', "%$kata%")
                  ->orWhere('tbl_pasar.alamat', 'LIKE', "%$kata%");
            });
        }

        $konsumens = $query->get();

        return view('pemilik.data_konsumen', compact('konsumens'));
    }
}
