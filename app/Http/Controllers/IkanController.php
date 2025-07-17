<?php

namespace App\Http\Controllers;

use App\Models\Ikan;
use Illuminate\Http\Request;
use DB;
class IkanController extends Controller
{
    public function index()
    {
        $ikans = Ikan::latest()->paginate(20);
        return view('admin.ikan.index',compact('ikans'))->with('i', (request()->input('page',1)-1)*20);
    }

    public function cari(Request $request)
    {
        $kata = $request->input('kata');
        $query = "jenis_ikan LIKE '%".$kata."%'
                  OR harga_beli LIKE '%".$kata."%'
                  OR harga_jual LIKE '%".$kata."%'
                  OR stok LIKE '%".$kata."%'";

        $ikans = DB::table('tbl_ikan')
                    ->whereRaw($query)
                    ->get();

        return view('admin.ikan.index', compact('ikans'))->with('i', (request()->input('page',1)-1)*20);
    }

    public function create()
    {
        return view('admin.ikan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_ikan' =>'required',
            'harga_beli' =>'required',
            'harga_jual' =>'required',
            'stok' =>'required',
        ]);

        Ikan::create([
            'jenis_ikan' =>$request->jenis_ikan,
            'harga_beli' =>$request->harga_beli,
            'harga_jual' =>$request->harga_jual,
            'stok' =>$request->stok,
        ]);
        return redirect()->route('ikan.index')
        ->with('success', 'Data ikan berhasil ditambahkan');
    }

    public function show($id)
    {
        //
    }

    public function edit(ikan $ikan)
    {
        return view('admin.ikan.edit', compact('ikan'));
    }

    public function update(Request $request, ikan $ikan)
    {
        var_dump($request->all());
        $request->validate([
            'jenis_ikan' =>'required',
            'harga_beli' =>'required',
            'harga_jual' =>'required',
            'stok' =>'required',
        ]);

        $ikan->update([
            'jenis_ikan' =>$request->jenis_ikan,
            'harga_beli' =>$request->harga_beli,
            'harga_jual' =>$request->harga_jual,
            'stok' =>$request->stok,
        ]);
        return redirect()->route('ikan.index')->with('success', 'Data Ikan Berhasil Diperbarui');
    }

    public function destroy(ikan $ikan)
    {
        $ikan->delete();
        return redirect()->route('ikan.index')->with('success', 'Data Ikan Berhasil Dihapus');
    }
}
