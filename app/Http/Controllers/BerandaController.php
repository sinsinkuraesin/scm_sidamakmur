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
        // 🔽 1. Downstream: Total pemasukan penjualan per bulan
        $penjualan = Jual::with('detailJual')->get();

        // Kita isi $konsumenTransaksi dengan total pemasukan per bulan
        $konsumenTransaksi = [];
        foreach (range(1, 12) as $bulan) {
            $total = $penjualan->filter(function ($jual) use ($bulan) {
                return Carbon::parse($jual->tgl_jual)->month == $bulan;
            })->sum(function ($jual) {
                return $jual->detailJual->sum('total');
            });

            $konsumenTransaksi[] = [
            'bulan' => $bulan,
            'total' => $total,
        ];

        }

        // 🔽 2. Internal Supply Chain: Rekap stok ikan per bulan dan jenis ikan
        $stokIkan = Ikan::select(
                DB::raw('MONTH(updated_at) as bulan'),
                'jenis_ikan',
                DB::raw('SUM(stok) as total')
            )
            ->groupBy('bulan', 'jenis_ikan')
            ->orderBy('bulan')
            ->get();

        // 🔽 3. Upstream: Total pembelian dari supplier per bulan
        $beli = Beli::select(
                DB::raw('MONTH(tgl_beli) as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Agar lengkap dari Januari–Desember
        $pembelian = [];
        foreach (range(1, 12) as $bulan) {
            $item = $beli->firstWhere('bulan', $bulan);
            $pembelian[] = [
                'bulan' => $bulan,
                'total' => $item ? $item->total : 0,
            ];
        }

        return view('admin.beranda', compact(
            'konsumenTransaksi', // → data pemasukan per bulan
            'stokIkan',          // → stok ikan per bulan per jenis
            'pembelian'          // → pembelian per bulan
        ));
    }
}
