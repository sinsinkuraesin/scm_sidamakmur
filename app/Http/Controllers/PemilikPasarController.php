<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasar;

class PemilikPasarController extends Controller
{
    public function index(Request $request)
    {
        $kata = $request->input('kata');

        $pasars = Pasar::when($kata, function ($query) use ($kata) {
                $query->where('nama_pasar', 'LIKE', "%$kata%")
                      ->orWhere('alamat', 'LIKE', "%$kata%")
                      ->orWhere('kd_pasar', 'LIKE', "%$kata%");
            })
            ->get();

        return view('pemilik.data_pasar', compact('pasars'));
    }
}
