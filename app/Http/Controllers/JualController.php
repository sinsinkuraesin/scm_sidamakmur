<?php

namespace App\Http\Controllers;

use App\Models\Jual;
use App\Models\Konsumen;
use App\Models\Ikan;
use App\Models\DetailJual;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class JualController extends Controller
{
    public function invoice(Jual $jual)
    {
        $jual->load('konsumen', 'detailJual.ikan');
        return view('admin.jual.invoice', compact('jual'));
    }

    public function invoicePdf(Jual $jual)
    {
        $jual->load('konsumen', 'detailJual.ikan');
        $pdf = Pdf::loadView('admin.jual.invoice', compact('jual'));
        return $pdf->download('invoice-jual-' . $jual->jual_id . '.pdf');
    }

    public function index()
    {
        $juals = Jual::with(['konsumen', 'detailJual.ikan'])->latest()->paginate(10);

        $totalPendapatan = DetailJual::sum('total');

        return view('admin.jual.index', compact('juals', 'totalPendapatan'));
    }

    public function cariju(Request $request)
    {
        $kata = $request->input('kata');

        $juals = Jual::with(['konsumen', 'detailJual.ikan'])
            ->where(function ($q) use ($kata) {
                $q->where('nama_pasar', 'like', "%$kata%")
                    ->orWhereHas('konsumen', function ($sub) use ($kata) {
                        $sub->where('nama_konsumen', 'like', "%$kata%");
                    })
                    ->orWhereHas('detailJual.ikan', function ($sub) use ($kata) {
                        $sub->where('jenis_ikan', 'like', "%$kata%");
                    });
            })
            ->latest()
            ->orWhere('kd_jual', 'like', "%$kata%")
            ->paginate(10);

             // Hitung total pendapatan dari data yang ditampilkan
           $totalPendapatan = $juals->getCollection()->flatMap(function ($jual) {
                return $jual->detailJual;
            })->sum('total');
        return view('admin.jual.index', compact('juals', 'totalPendapatan'));
    }

    public function carij(Request $request)
    {
        $request->validate([
            'dari' => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari',
        ]);

        $juals = Jual::with(['konsumen', 'detailJual.ikan'])
            ->whereBetween('tgl_jual', [$request->dari, $request->sampai])
            ->latest()
            ->paginate(10);

         // Hitung total pendapatan dari data yang ditampilkan
       $totalPendapatan = $juals->getCollection()->flatMap(function ($jual) {
            return $jual->detailJual;
        })->sum('total');
    return view('admin.jual.index', compact('juals', 'totalPendapatan'));
    }

    public function create()
    {
        $konsumen = DB::table('tbl_konsumen')
            ->join('tbl_pasar', 'tbl_konsumen.nama_pasar', '=', 'tbl_pasar.id')
            ->select('tbl_konsumen.*', 'tbl_pasar.nama_pasar as nama_pasar_asli')
            ->get();

        $ikan = Ikan::all();
        return view('admin.jual.create', compact('konsumen', 'ikan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_jual' => 'required|string',
            'nama_konsumen' => 'required|exists:tbl_konsumen,id',
            'tgl_jual' => 'required|date',
            'nama_pasar' => 'required|string',
            'ikan' => 'required|array',
            'status' => 'required|in:Diproses,Selesai',
        ]);

        $ikanDipilih = array_filter($request->ikan, function ($ikan) {
            return isset($ikan['checked']) && $ikan['checked'] == 1;
        });

        if (count($ikanDipilih) === 0) {
            return back()->withInput()->with('error', 'Pilih minimal satu jenis ikan untuk dijual.');
        }

        DB::beginTransaction();
        try {
            // Simpan data jual utama
            $jual = Jual::create([
                'kd_jual' => $request->kd_jual,
                'nama_konsumen' => $request->nama_konsumen,
                'tgl_jual' => $request->tgl_jual,
                'nama_pasar' => $request->nama_pasar,
                'status' => $request->status,
            ]);

            // Simpan detail penjualan
            foreach ($ikanDipilih as $ikan) {
                DetailJual::create([
                    'jual_id' => $jual->jual_id,
                    'kd_jual' => $jual->kd_jual,
                    'jenis_ikan' => $ikan['id'],
                    'harga_jual' => $ikan['harga'],
                    'jml_ikan' => $ikan['jumlah'],
                    'total' => $ikan['total'],
                ]);

                // Update stok ikan hanya jika statusnya "Selesai"
                if ($request->status === 'Selesai') {
                    $ikanModel = Ikan::findOrFail($ikan['id']);
                    $ikanModel->stok -= $ikan['jumlah'];
                    if ($ikanModel->stok < 0) {
                        throw new \Exception("Stok ikan {$ikanModel->jenis_ikan} tidak mencukupi.");
                    }
                    $ikanModel->save();
                }
            }

            DB::commit();
            return redirect()->route('jual.index')->with('success', 'Data Penjualan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan penjualan: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data penjualan: ' . $e->getMessage());
        }
    }

    public function edit(Jual $jual)
    {
        $konsumen = DB::table('tbl_konsumen')
            ->join('tbl_pasar', 'tbl_konsumen.nama_pasar', '=', 'tbl_pasar.id')
            ->select('tbl_konsumen.*', 'tbl_pasar.nama_pasar as nama_pasar_asli')
            ->get();

        $ikan = Ikan::all();
        $jual->load('detailJual.ikan');

        return view('admin.jual.edit', compact('jual', 'konsumen', 'ikan'));
    }

    public function update(Request $request, Jual $jual)
    {
        $request->validate([
            'kd_jual' => 'required|string',
            'nama_konsumen' => 'required|exists:tbl_konsumen,id',
            'tgl_jual' => 'required|date',
            'nama_pasar' => 'required|string',
            'ikan' => 'required|array',
            'status' => 'required|in:Diproses,Selesai',
            'total' => 'required|numeric|min:0',
        ]);

        $ikanDipilih = collect($request->ikan)->filter(function ($ikan) {
            return isset($ikan['checked']) && $ikan['checked'] == 1;
        });

        if ($ikanDipilih->isEmpty()) {
            return back()->withErrors(['ikan' => 'Pilih minimal satu ikan untuk dijual.'])->withInput();
        }

        DB::beginTransaction();
        try {
            // Simpan status lama sebelum diupdate
            $oldStatus = $jual->status;

            // Kembalikan stok ikan kalau status lama adalah 'Selesai'
            if ($oldStatus === 'Selesai') {
                foreach ($jual->detailJual as $detail) {
                    $ikan = Ikan::find($detail->jenis_ikan);
                    if ($ikan) {
                        $ikan->stok += $detail->jml_ikan;
                        $ikan->save();
                    }
                }
            }

            // Update data penjualan
            $jual->update([
                'kd_jual' => $request->kd_jual,
                'nama_konsumen' => $request->nama_konsumen,
                'tgl_jual' => $request->tgl_jual,
                'nama_pasar' => $request->nama_pasar,
                'total' => $request->total,
                'status' => $request->status,
            ]);

            // Hapus detail lama
            DetailJual::where('jual_id', $jual->jual_id)->delete();

            // Simpan detail baru
            foreach ($ikanDipilih as $data) {
                DetailJual::create([
                    'jual_id' => $jual->jual_id,
                    'kd_jual' => $jual->kd_jual,
                    'jenis_ikan' => $data['id'],
                    'harga_jual' => $data['harga'],
                    'jml_ikan' => $data['jumlah'],
                    'total' => $data['total'],
                ]);
            }

            // Kurangi stok kalau status baru 'Selesai'
            if ($request->status === 'Selesai') {
                foreach ($ikanDipilih as $data) {
                    $ikanModel = Ikan::findOrFail($data['id']);
                    $ikanModel->stok -= $data['jumlah'] ?? 0;
                    if ($ikanModel->stok < 0) {
                        throw new \Exception("Stok ikan {$ikanModel->jenis_ikan} tidak mencukupi.");
                    }
                    $ikanModel->save();
                }
            }

            DB::commit();
            return redirect()->route('jual.index')->with('success', 'Data penjualan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Jual $jual)
    {
        DB::beginTransaction();
        try {
            foreach ($jual->detailJual as $detail) {
                $ikan = Ikan::find($detail->jenis_ikan);
                if ($ikan) {
                    $ikan->stok += $detail->jml_ikan;
                    $ikan->save();
                }
            }

            $jual->detailJual()->delete();
            $jual->delete();

            DB::commit();
            return redirect()->route('jual.index')->with('success', 'Data Penjualan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus penjualan: ' . $e->getMessage());
        }
    }
}
