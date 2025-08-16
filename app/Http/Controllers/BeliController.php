<?php

namespace App\Http\Controllers;

use App\Models\Beli;
use App\Models\Supplier;
use App\Models\Ikan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $belis = Beli::with(['supplier', 'ikan'])->latest()->paginate(100);
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
            ->orWhere('kd_beli', 'like', "%$kata%")
            ->orWhere('jml_ikan', 'like', "%$kata%")
            ->orWhere('harga_beli', 'like', "%$kata%")
            ->orWhere('total_harga', 'like', "%$kata%")
            ->latest()
            ->paginate(10);

        $totalPengeluaran = $belis->sum('total_harga');

        return view('admin.beli.index', compact('belis', 'totalPengeluaran'));
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

        $totalPengeluaran = $belis->sum('total_harga');

        return view('admin.beli.index', compact('belis', 'totalPengeluaran'));
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
            'kd_beli' => 'required',
            'kd_supplier' => 'required|exists:tbl_supplier,id',
            'jenis_ikan'  => 'required|exists:tbl_ikan,id',
            'tgl_beli'    => 'required|date',
            'jml_ikan'    => 'required|numeric|min:20',
            'harga_beli'  => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Letakkan di sini supaya $path tetap bisa digunakan di dalam try
        $path = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        DB::beginTransaction();
        try {
            $transaksi = Beli::create([
                'kd_beli' => $request->kd_beli,
                'kd_supplier' => $request->kd_supplier,
                'jenis_ikan'  => $request->jenis_ikan,
                'tgl_beli'    => $request->tgl_beli,
                'jml_ikan'    => $request->jml_ikan,
                'harga_beli'  => $request->harga_beli,
                'total_harga' => $request->total_harga,
                'bukti_pembayaran' => $path // <- ini tidak akan error meski null
            ]);

            if ($path) { // Jika sudah upload bukti pembayaran
                $ikan = Ikan::findOrFail($request->jenis_ikan);
                $ikan->stok += $request->jml_ikan;
                $ikan->save();
            }

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
            'kd_beli' => 'required',
            'kd_supplier' => 'required|exists:tbl_supplier,id',
            'jenis_ikan'  => 'required|exists:tbl_ikan,id',
            'tgl_beli'    => 'required|date',
            'jml_ikan'    => 'required|numeric|min:20',
            'harga_beli'  => 'required|numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $ikan = Ikan::findOrFail($request->jenis_ikan);
            $stokBertambah = false;
            $pathBaru = $beli->bukti_pembayaran;

            // Jika sebelumnya belum ada bukti pembayaran, dan sekarang diupload
            if (!$beli->bukti_pembayaran && $request->hasFile('bukti_pembayaran')) {
                $pathBaru = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
                $ikan->stok += $request->jml_ikan;
                $ikan->save();
                $stokBertambah = true;
            }

            // Hapus bukti lama jika ingin mengganti (opsional, tergantung kebutuhan)
            if ($beli->bukti_pembayaran && $request->hasFile('bukti_pembayaran')) {
                if (Storage::disk('public')->exists($beli->bukti_pembayaran)) {
                    Storage::disk('public')->delete($beli->bukti_pembayaran);
                }
                $pathBaru = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }

            $beli->update([
                'kd_beli' => $request->kd_beli,
                'kd_supplier' => $request->kd_supplier,
                'jenis_ikan'  => $request->jenis_ikan,
                'tgl_beli'    => $request->tgl_beli,
                'jml_ikan'    => $request->jml_ikan,
                'harga_beli'  => $request->harga_beli,
                'total_harga' => $request->total_harga,
                'bukti_pembayaran' => $pathBaru
            ]);

            DB::commit();
            return redirect()->route('beli.index')->with('success', 'Data pembelian berhasil diperbarui.' . ($stokBertambah ? ' Stok ikan telah ditambahkan.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui pembelian: ' . $e->getMessage());
        }
    }

    public function destroy(Beli $beli)
    {
        DB::beginTransaction();
        try {
            // Kurangi stok hanya kalau sudah upload bukti bayar
            if ($beli->bukti_pembayaran) {
                $ikan = Ikan::findOrFail($beli->jenis_ikan);
                $ikan->stok -= $beli->jml_ikan;
                if ($ikan->stok < 0) {
                    $ikan->stok = 0; // Hindari stok minus
                }
                $ikan->save();
            }

            // Hapus bukti pembayaran jika ada
            if ($beli->bukti_pembayaran && Storage::disk('public')->exists($beli->bukti_pembayaran)) {
                Storage::disk('public')->delete($beli->bukti_pembayaran);
            }

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
