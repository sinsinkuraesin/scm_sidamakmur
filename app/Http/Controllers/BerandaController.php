<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jual;
use App\Models\Beli;
use App\Models\Ikan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index()
    {
        $tahunIni = Carbon::now()->year;

        // 1. Data Penjualan Bulanan
        $penjualan = Jual::with('detailJual')
            ->whereYear('tgl_jual', $tahunIni)
            ->get();

        $konsumenTransaksi = [];
        foreach (range(1, 12) as $bulan) {
            $total = $penjualan->filter(function ($jual) use ($bulan) {
                return Carbon::parse($jual->tgl_jual)->month == $bulan;
            })->sum(function ($jual) {
                return $jual->detailJual->sum('total');
            });

            $konsumenTransaksi[] = [
                'bulan' => Carbon::create(null, $bulan)->format('M'),
                'total' => $total,
            ];
        }

        // 2. Data Pembelian Bulanan
        $pembelianData = Beli::select(
                DB::raw('MONTH(tgl_beli) as bulan'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereYear('tgl_beli', $tahunIni)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $pembelian = [];
        foreach (range(1, 12) as $bulan) {
            $data = $pembelianData->firstWhere('bulan', $bulan);
            $pembelian[] = [
                'bulan' => Carbon::create(null, $bulan)->format('M'),
                'total' => $data->total ?? 0,
            ];
        }

      // 3. Data Stok Harian per Ikan (1 minggu ini)
        $hariIni = Carbon::now();
        $tanggalMulai = $hariIni->copy()->startOfWeek(); // Senin minggu ini
        $tanggalAkhir = $hariIni->copy()->endOfWeek();   // Minggu minggu ini

        $stokData = Ikan::select(
                DB::raw("DATE(updated_at) as tanggal"),
                'jenis_ikan',
                DB::raw('SUM(stok) as total')
            )
            ->whereBetween('updated_at', [$tanggalMulai, $tanggalAkhir])
            ->groupBy('tanggal', 'jenis_ikan')
            ->orderBy('tanggal')
            ->get();

        // Buat label per hari dari Senin sampai Minggu
        $tanggalKeys = collect();
        for ($date = $tanggalMulai->copy(); $date <= $tanggalAkhir; $date->addDay()) {
            $tanggalKeys->push($date->format('Y-m-d'));
        }

        $tanggalLabels = $tanggalKeys->map(fn($tgl) => Carbon::parse($tgl)->format('d M'));

        $jenisIkanList = $stokData->pluck('jenis_ikan')->unique();
        $stokChartData = [];

        foreach ($jenisIkanList as $jenis) {
            $dataPerHari = [];
            foreach ($tanggalKeys as $tgl) {
                $data = $stokData->first(fn($item) => $item->tanggal === $tgl && $item->jenis_ikan === $jenis);
                $dataPerHari[] = $data ? $data->total : 0;
            }

            $stokChartData[] = [
                'label' => $jenis,
                'data' => $dataPerHari,
            ];
        }

        return view('admin.beranda', compact(
            'konsumenTransaksi',
            'pembelian',
            'stokChartData',
            'tanggalLabels'
        ));
    }
}
