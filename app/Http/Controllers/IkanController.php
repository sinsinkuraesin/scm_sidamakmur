<?php

namespace App\Http\Controllers;

use App\Models\Ikan;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class IkanController extends Controller
{
   public function index(Request $request)
    {
        $tanggal = $request->input('tanggal') ?? Carbon::today()->toDateString();
        $ikans = Ikan::with(['belis', 'detailJual'])->get();

        foreach ($ikans as $ikan) {
            // Hitung jumlah pembelian ikan sampai tanggal yang dipilih
            $jumlahMasuk = $ikan->belis()
                ->whereDate('tgl_beli', '<=', $tanggal)
                ->sum('jml_ikan');

            // Hitung jumlah penjualan ikan sampai tanggal yang dipilih
            $jumlahKeluar = $ikan->detailJual()
                ->whereHas('jual', function ($q) use ($tanggal) {
                    $q->whereDate('tgl_jual', '<=', $tanggal);
                })
                ->sum('jml_ikan');

            $ikan->stok_per_tanggal = $jumlahMasuk - $jumlahKeluar;
        }

        return view('admin.ikan.index', compact('ikans', 'tanggal'))->with('i', 0);
    }


    public function cari(Request $request)
    {
        $kata = $request->input('kata');
        $query = "jenis_ikan LIKE '%".$kata."%'
                  OR kd_ikan LIKE '%".$kata."%'
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
            'kd_ikan' =>'required',
            'foto_ikan' => 'required',
            'jenis_ikan' =>'required',
            'harga_beli' =>'required',
            'harga_jual' =>'required',
            'stok' =>'required',
        ]);

        $file = $request->file('foto_ikan');
        $nama_file = time()."_".$file->getClientOriginalName(); //mengubah nama file
        $tujuan_upload = "Foto_Ikan";
        $file->move($tujuan_upload,$nama_file); //akan disesuaikan


        Ikan::create([
            'kd_ikan' =>$request->kd_ikan,
            'foto_ikan' => $nama_file,
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

    public function update(Request $request, Ikan $ikan)
    {
        $request->validate([
            'kd_ikan' => 'required',
            'foto_ikan' => 'nullable|image',
            'jenis_ikan' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'stok' => 'required',
        ]);

        if ($request->hasFile('foto_ikan')) {
            // hapus foto lama
            if ($ikan->foto_ikan && file_exists(public_path('Foto_Ikan/' . $ikan->foto_ikan))) {
                unlink(public_path('Foto_Ikan/' . $ikan->foto_ikan));
            }

            $file = $request->file('foto_ikan');
            $nama_file = time() . "_" . $file->getClientOriginalName();
            $file->move(public_path('Foto_Ikan'), $nama_file);

            $ikan->update([
                'kd_ikan' => $request->kd_ikan,
                'foto_ikan' => $nama_file,
                'jenis_ikan' => $request->jenis_ikan,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok' => $request->stok,
            ]);
        } else {
            // update tanpa ganti foto
            $ikan->update([
                'kd_ikan' => $request->kd_ikan,
                'jenis_ikan' => $request->jenis_ikan,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'stok' => $request->stok,
            ]);
        }

        return redirect()->route('ikan.index')->with('success', 'Data Ikan Berhasil Diperbarui');
    }
    public function destroy(ikan $ikan)
    {
        unlink(public_path('Foto_Ikan/'.$ikan->foto_ikan));
        $ikan->delete();
        return redirect()->route('ikan.index')->with('success', 'Data Ikan Berhasil Dihapus');
    }
}
