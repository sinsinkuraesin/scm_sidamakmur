<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jual;
use App\Models\Beli;
use App\Models\Ikan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PemilikBerandaController extends Controller
{
    public function index()
    {
        $tahunIni = Carbon::now()->year;

        // 1. Data Penjualan per bulan
        $penjualan = Jual::with('detailJual')
            ->whereYear('tgl_jual', $tahunIni)
            ->get();

        $konsumenTransaksi = [];
        foreach (range(1, 12) as $bulan) {
            $total = $penjualan->filter(fn($jual) => Carbon::parse($jual->tgl_jual)->month == $bulan)
                ->sum(fn($jual) => $jual->detailJual->sum('total'));

            $konsumenTransaksi[] = $total;
        }

        // 2. Data Pembelian per bulan
        $pembelianData = Beli::select(
                DB::raw('MONTH(tgl_beli) as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tgl_beli', $tahunIni)
            ->groupBy('bulan')
            ->get();

        $pembelian = array_fill(0, 12, 0);
        foreach ($pembelianData as $item) {
            $pembelian[$item->bulan - 1] = $item->total;
        }

        // 3. Data Stok Bulanan per Jenis Ikan
        $stokData = Ikan::select(
                DB::raw('MONTH(updated_at) as bulan'),
                'jenis_ikan',
                DB::raw('SUM(stok) as total')
            )
            ->whereYear('updated_at', $tahunIni)
            ->groupBy('bulan', 'jenis_ikan')
            ->get();

        $jenisIkan = $stokData->pluck('jenis_ikan')->unique();

        $stokChartData = [];
        foreach ($jenisIkan as $jenis) {
            $dataPerBulan = array_fill(0, 12, 0);

            foreach ($stokData->where('jenis_ikan', $jenis) as $item) {
                $dataPerBulan[$item->bulan - 1] = $item->total;
            }

            $stokChartData[] = [
                'label' => $jenis,
                'data' => $dataPerBulan,
            ];
        }
        // Tambahan: Label Tanggal 7 Hari Terakhir
        $tanggalLabels = collect(range(0, 6))
            ->map(fn($i) => Carbon::now()->subDays(6 - $i)->format('d M'))
            ->toArray();


        return view('pemilik.beranda', compact(
            'pembelian',
            'stokChartData',
            'konsumenTransaksi',
            'tanggalLabels'
        ));
    }
}
