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

        // 3. Data Stok Harian per Ikan (1 minggu ini, kumulatif)
        $hariIni = Carbon::now();
        $tanggalMulai = $hariIni->copy()->subDays(6); // 6 hari ke belakang
        $tanggalAkhir = $hariIni->copy();             // Hari ini

        $tanggalKeys = collect();
        for ($date = $tanggalMulai->copy(); $date <= $tanggalAkhir; $date->addDay()) {
            $tanggalKeys->push($date->format('Y-m-d'));
        }

        $tanggalLabels = $tanggalKeys->map(fn($tgl) => Carbon::parse($tgl)->format('d M'));

        $ikanList = Ikan::with(['belis', 'detailJual'])->get();
        $stokChartData = [];

        foreach ($ikanList as $ikan) {
            $dataPerHari = [];

            foreach ($tanggalKeys as $tgl) {
                // Total pembelian sampai tanggal itu
                $jumlahMasuk = $ikan->belis()
                    ->whereDate('tgl_beli', '<=', $tgl)
                    ->sum('jml_ikan');

                // Total penjualan sampai tanggal itu
                $jumlahKeluar = $ikan->detailJual()
                    ->whereHas('jual', function ($q) use ($tgl) {
                        $q->whereDate('tgl_jual', '<=', $tgl);
                    })
                    ->sum('jml_ikan');

                $stok = $jumlahMasuk - $jumlahKeluar;
                $dataPerHari[] = $stok;
            }

            $stokChartData[] = [
                'label' => $ikan->jenis_ikan,
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
