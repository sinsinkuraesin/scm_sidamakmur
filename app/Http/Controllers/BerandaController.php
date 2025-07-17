<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jual;
use App\Models\Beli;
use App\Models\Ikan;
use App\Models\Konsumen;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index()
    {
        // Konsumen teratas
        $konsumenTransaksi = Jual::select('nama_konsumen', DB::raw('COUNT(*) as jumlah'))
        ->groupBy('nama_konsumen')
        ->orderByDesc('jumlah')
        ->take(10)
        ->get()
        ->map(function ($item) {
            $konsumen = Konsumen::find($item->nama_konsumen);
            $item->nama_konsumen = $konsumen ? $konsumen->nama_konsumen : 'Tidak Diketahui';
            return $item;
        });


        // Stok Ikan per bulan
        $stokIkan = Ikan::select(
            DB::raw('MONTH(updated_at) as bulan'),
            'jenis_ikan',
            DB::raw('SUM(stok) as total')
        )
        ->groupBy('bulan', 'jenis_ikan')
        ->orderBy('bulan')
        ->get();

        // Ambil semua penjualan dan pembelian, lalu proses manual
        $penjualan = Jual::with('detailJual')->get();
        $pembelian = Beli::all();

        // Pemasukan per bulan
        $pemasukan = [];
        foreach (range(1, 12) as $bulan) {
            $pemasukan[] = [
                'bulan' => $bulan,
                'total' => $penjualan->filter(function ($jual) use ($bulan) {
                    return Carbon::parse($jual->tgl_jual)->month == $bulan;
                })->sum(function ($jual) {
                    return $jual->detailJual->sum('total');
                }),
            ];
        }

        // Pengeluaran per bulan
        $pengeluaran = [];
        foreach (range(1, 12) as $bulan) {
            $pengeluaran[] = [
                'bulan' => $bulan,
                'total' => $pembelian->filter(function ($beli) use ($bulan) {
                    return Carbon::parse($beli->tgl_beli)->month == $bulan;
                })->sum('total_harga'),
            ];
        }

        return view('admin.beranda', compact(
            'konsumenTransaksi',
            'stokIkan',
            'pemasukan',
            'pengeluaran'
        ));
    }
}
