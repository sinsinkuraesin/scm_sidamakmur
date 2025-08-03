<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class PemilikSupplierController extends Controller
{
    public function index(Request $request)
    {
        $kata = $request->input('kata');

        $suppliers = \App\Models\Supplier::when($kata, function ($query) use ($kata) {
                $query->where('nm_supplier', 'LIKE', "%$kata%")
                    ->orWhere('kd_supplier', 'LIKE', "%$kata%")
                    ->orWhereHas('ikan', function ($q) use ($kata) {
                        $q->where('jenis_ikan', 'like', "%$kata%");
                    })
                    ->orWhere('alamat', 'LIKE', "%$kata%");
            })
            ->get();

        $ikans = \App\Models\Ikan::all()->keyBy('id'); // ambil semua ikan, index berdasarkan id

        return view('pemilik.data_supplier', compact('suppliers', 'ikans'));
    }
}
