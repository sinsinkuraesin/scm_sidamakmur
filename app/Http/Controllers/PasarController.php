<?php

namespace App\Http\Controllers;

use App\Models\Pasar;
use Illuminate\Http\Request;
use DB;
class PasarController extends Controller
{
    public function index()
    {
        $pasars = Pasar::latest()->paginate(20);
        return view('admin.pasar.index',compact('pasars'))->with('i', (request()->input('page',1)-1)*20);
    }

    public function carips(Request $request)
    {
        $kata = $request->input('kata');
        $query = "nama_pasar LIKE '%".$kata."%'
                  OR alamat LIKE '%".$kata."%'";

        $pasars = DB::table('tbl_pasar')
                    ->whereRaw($query)
                    ->get();

        return view('admin.pasar.index', compact('pasars'))->with('i', (request()->input('page',1)-1)*20);
    }

    public function create()
    {
        return view('admin.pasar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pasar' =>'required',
            'alamat' =>'required',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
        ]);

        Pasar::create([
            'nama_pasar' =>$request->nama_pasar,
            'alamat' =>$request->alamat,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
        ]);
        return redirect()->route('pasar.index')
        ->with('success', 'Data pasar berhasil ditambahkan');
    }

    public function show($id)
    {
        //
    }

    public function edit(Pasar $pasar)
    {
        return view('admin.pasar.edit', compact('pasar'));
    }

    public function update(Request $request, Pasar $pasar)
    {
        var_dump($request->all());
        $request->validate([
            'nama_pasar' =>'required',
            'alamat' =>'required',
            'jam_buka' => 'required',
            'jam_tutup' => 'required',
        ]);

        $pasar->update([
            'nama_pasar' =>$request->nama_pasar,
            'alamat' =>$request->alamat,
            'jam_buka' => $request->jam_buka,
            'jam_tutup' => $request->jam_tutup,
        ]);
        return redirect()->route('pasar.index')->with('success', 'Data pasar Berhasil Diperbarui');
    }

    public function destroy(Pasar $pasar)
    {
        $pasar->delete();
        return redirect()->route('pasar.index')->with('success', 'Data pasar Berhasil Dihapus');
    }
}
