<?php

namespace App\Http\Controllers;
use App\Models\Konsumen;
use App\Models\Pasar;
use Illuminate\Http\Request;
use DB;

class KonsumenController extends Controller
{
    public function index()
    {
        $konsumens = DB::table('tbl_konsumen')
            ->join('tbl_pasar', 'tbl_konsumen.nama_pasar', '=', 'tbl_pasar.id')
            ->select(
                'tbl_konsumen.*',
                'tbl_pasar.nama_pasar as nama_pasar',
                'tbl_pasar.alamat as alamat',
                'tbl_pasar.jam_buka',
                'tbl_pasar.jam_tutup'
            )
            ->paginate(20);

        return view('admin.konsumen.index', compact('konsumens'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function create()
    {
        $pasar = DB::table('tbl_pasar')->get();
        return view('admin.konsumen.create', compact('pasar'));
    }

   public function store(Request $request)
    {
    $request->validate([
        'nama_konsumen' => 'required|string|max:100',
        'nama_pasar'    => 'required|exists:tbl_pasar,id',
    ]);

    $pasar = Pasar::findOrFail($request->nama_pasar);

    Konsumen::create([
        'kd_konsumen' => $request->kd_konsumen,
        'nama_konsumen' => $request->nama_konsumen,
        'no_tlp' => $request->no_tlp,
        'nama_pasar'    => $pasar->id,
        'alamat'        => $pasar->alamat,
        'jam_buka'      => $pasar->jam_buka,
        'jam_tutup'     => $pasar->jam_tutup,
    ]);

    return redirect()->route('konsumen.index')->with('success', 'Data konsumen berhasil ditambahkan.');
    }

   public function edit($id)
{
    $konsumen = Konsumen::findOrFail($id);
    $pasar = Pasar::all();

    return view('admin.konsumen.edit', compact('konsumen', 'pasar'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_konsumen' => 'required',
        'nama_pasar' => 'required|exists:tbl_pasar,id',
    ]);

    $konsumen = Konsumen::findOrFail($id);
    $pasar = Pasar::findOrFail($request->nama_pasar);

    $konsumen->update([
        'kd_konsumen' => $request->kd_konsumen,
        'nama_konsumen' => $request->nama_konsumen,
        'no_tlp' => $request->no_tlp,
        'nama_pasar'    => $pasar->id,
        'alamat'        => $pasar->alamat,
        'jam_buka'      => $pasar->jam_buka,
        'jam_tutup'     => $pasar->jam_tutup,
    ]);

    return redirect()->route('konsumen.index')->with('success', 'Data konsumen berhasil diperbarui.');
}


   public function destroy($id)
    {
    $konsumen = Konsumen::findOrFail($id);
    $konsumen->delete();

    return redirect()->route('konsumen.index')->with('success', 'Data konsumen berhasil dihapus.');
    }

}
