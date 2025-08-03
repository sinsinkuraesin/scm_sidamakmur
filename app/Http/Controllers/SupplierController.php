<?php

namespace App\Http\Controllers;

Use App\Models\Supplier;
Use App\Models\Ikan;
use Illuminate\Http\Request;
use DB;

class SupplierController extends Controller
{

    public function index()
    {
        $suppliers = DB::table('tbl_supplier')
            ->join('tbl_ikan', 'tbl_ikan.id', '=', 'tbl_supplier.jenis_ikan')
            ->select('tbl_supplier.*', 'tbl_ikan.jenis_ikan as jenis_ikan')
            ->get();

        return view('admin.supplier.index', compact('suppliers'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function carisp(Request $request)
    {
        $kata = $request->input('kata');

        $suppliers = DB::table('tbl_supplier')
            ->join('tbl_ikan', 'tbl_ikan.id', '=', 'tbl_supplier.jenis_ikan')
            ->where(function ($query) use ($kata) {
                $query->where('tbl_supplier.kd_supplier', 'LIKE', "%$kata%")
                    ->orWhere('tbl_supplier.nm_supplier', 'LIKE', "%$kata%")
                    ->orWhere('tbl_ikan.jenis_ikan', 'LIKE', "%$kata%")
                    ->orWhere('tbl_supplier.alamat', 'LIKE', "%$kata%");
            })
            ->select('tbl_supplier.*', 'tbl_ikan.jenis_ikan as jenis_ikan')
            ->get();

        return view('admin.supplier.index', compact('suppliers'))
            ->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function create()
    {
        $ikan = Ikan::select('id', 'jenis_ikan')->get();
        return view('admin.supplier.create', compact('ikan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_supplier' =>'required',
            'nm_supplier' =>'required',
            'jenis_ikan' =>'required',
            'alamat' =>'required',
        ]);

        Supplier::create([
            'kd_supplier' =>$request->kd_supplier,
            'nm_supplier' =>$request->nm_supplier,
            'jenis_ikan' =>$request->jenis_ikan,
            'alamat' =>$request->alamat,
        ]);
        return redirect()->route('supplier.index')
        ->with('success', 'Data supplier berhasil ditambahkan');
    }

    public function show($id)
    {
        //
    }


    public function edit(Supplier $supplier)
    {
        $ikan = Ikan::select('id', 'jenis_ikan')->get();
        return view('admin.supplier.edit', compact('supplier', 'ikan'));
    }


    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'kd_supplier' =>'required',
            'nm_supplier' =>'required',
            'jenis_ikan' =>'required',
            'alamat' =>'required',
        ]);

        $supplier->update([
            'kd_supplier' =>$request->kd_supplier,
            'nm_supplier' =>$request->nm_supplier,
            'jenis_ikan' =>$request->jenis_ikan,
            'alamat' =>$request->alamat,
        ]);
        return redirect()->route('supplier.index')
        ->with('success', 'Data supplier berhasil diperbarui');
    }


    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil dihapus.');
    }
}
