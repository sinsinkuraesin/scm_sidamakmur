<?php

namespace App\Http\Controllers;

use App\Models\Beli;
use App\Models\Supplier;
use App\Models\Ikan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class BeliController extends Controller
{
    public function invoice($id)
    {
        $beli = Beli::with(['supplier', 'ikan'])->findOrFail($id);
        return view('admin.beli.invoice', compact('beli'));
    }

    public function cetakInvoicePDF($id)
    {
        $beli = Beli::with(['supplier', 'ikan'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.beli.invoice', compact('beli'));
        return $pdf->stream('invoice-pembelian-'.$id.'.pdf');
    }
    public function index()
    {
        $belis = Beli::with(['supplier', 'ikan'])->latest()->paginate(50);
        $totalPengeluaran = $belis->sum('total_harga');

        return view('admin.beli.index', compact('belis', 'totalPengeluaran'));
    }

    public function caribe(Request $request)
    {
        $kata = $request->input('kata');

        $belis = Beli::with(['supplier', 'ikan'])
            ->whereHas('supplier', function ($q) use ($kata) {
                $q->where('kd_supplier', 'like', "%$kata%");
            })
            ->orWhereHas('ikan', function ($q) use ($kata) {
                $q->where('jenis_ikan', 'like', "%$kata%");
            })
            ->orWhere('jml_ikan', 'like', "%$kata%")
            ->orWhere('harga_beli', 'like', "%$kata%")
            ->orWhere('total_harga', 'like', "%$kata%")
            ->latest()
            ->paginate(10);

        return view('admin.beli.index', compact('belis'));
    }

    public function carib(Request $request)
    {
        $request->validate([
            'dari' => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari',
        ]);

        $belis = Beli::with(['supplier', 'ikan'])
            ->whereBetween('tgl_beli', [$request->dari, $request->sampai])
            ->latest()
            ->paginate(10);

        return view('admin.beli.index', compact('belis'));
    }

    public function create()
    {
        $supplier = Supplier::all();
        $ikan = Ikan::all();
        return view('admin.beli.create', compact('supplier', 'ikan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kd_supplier' => 'required|exists:tbl_supplier,id',
            'jenis_ikan'  => 'required|exists:tbl_ikan,id',
            'tgl_beli'    => 'required|date',
            'jml_ikan'    => 'required|numeric|min:1',
            'harga_beli'  => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Beli::create($request->only([
                'kd_supplier', 'jenis_ikan', 'tgl_beli', 'jml_ikan', 'harga_beli', 'total_harga'
            ]));

            $ikan = Ikan::findOrFail($request->jenis_ikan);
            $ikan->stok += $request->jml_ikan;
            $ikan->save();

            DB::commit();
            return redirect()->route('beli.invoice', $transaksi->id);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan pembelian: ' . $e->getMessage());
        }
    }


    public function getIkanBySupplier($supplierId)
    {
        $supplier = \App\Models\Supplier::with('ikan')->find($supplierId);

        if (!$supplier || !$supplier->ikan) {
            return response()->json(['error' => 'Data ikan tidak ditemukan untuk supplier ini.'], 404);
        }

        return response()->json([
            'id' => $supplier->ikan->id,
            'jenis_ikan' => $supplier->ikan->jenis_ikan,
            'harga_beli' => $supplier->ikan->harga_beli
        ]);
    }


    public function edit(Beli $beli)
    {
        $supplier = Supplier::all();
        $ikan = Ikan::all();
        return view('admin.beli.edit', compact('beli', 'supplier', 'ikan'));
    }

    public function update(Request $request, Beli $beli)
    {
        $request->validate([
            'kd_supplier' => 'required|exists:tbl_supplier,id',
            'jenis_ikan'  => 'required|exists:tbl_ikan,id',
            'tgl_beli'    => 'required|date',
            'jml_ikan'    => 'required|numeric|min:1',
            'harga_beli'  => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Ambil stok ikan saat ini
            $ikan = Ikan::findOrFail($request->jenis_ikan);

            // Koreksi stok: Kurangi jumlah lama, tambahkan jumlah baru
            $stokAwal = $beli->jml_ikan;
            $stokBaru = $request->jml_ikan;
            $selisih = $stokBaru - $stokAwal;
            $ikan->stok += $selisih;
            $ikan->save();

            // Update data pembelian
            $beli->update($request->only([
                'kd_supplier', 'jenis_ikan', 'tgl_beli', 'jml_ikan', 'harga_beli', 'total_harga'
            ]));

            DB::commit();
            return redirect()->route('beli.index')->with('success', 'Pembelian berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui pembelian: ' . $e->getMessage());
        }
    }

    public function destroy(Beli $beli)
    {
        DB::beginTransaction();
        try {
            // Kurangi stok sesuai jumlah ikan yang pernah dibeli
            $ikan = Ikan::findOrFail($beli->jenis_ikan);
            $ikan->stok -= $beli->jml_ikan;
            if ($ikan->stok < 0) {
                $ikan->stok = 0; // Hindari stok minus
            }
            $ikan->save();

            // Hapus data pembelian
            $beli->delete();

            DB::commit();
            return redirect()->route('beli.index')->with('success', 'Pembelian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus pembelian: ' . $e->getMessage());
        }
    }

}
