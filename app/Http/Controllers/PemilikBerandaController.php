<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jual;
use App\Models\Supplier;
use App\Models\DetailJual;
use App\Models\Beli;
use App\Models\Konsumen;
use App\Models\Ikan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PemilikBerandaController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::now();
        $tanggalMulai = $hariIni->copy()->subDays(6); // 7 hari terakhir

        // Generate daftar tanggal dan label
        $tanggalKeys = collect();
        for ($date = $tanggalMulai->copy(); $date <= $hariIni; $date->addDay()) {
            $tanggalKeys->push($date->format('Y-m-d'));
        }
        $tanggalLabels = $tanggalKeys->map(fn($tgl) => Carbon::parse($tgl)->format('d M'));

        // Warna sesuai Grafik 2
        $colorMap = [
            'Ikan Gurame' => '#42a5f5',
            'Ikan Lele'   => '#66bb6a',
            'Ikan Mas'    => '#ffa726',
            'Ikan Mujair' => '#ab47bc',
        ];

        // === 1. SUPPLIER per hari (Top 5) ===
        $supplierData = Beli::select(
                DB::raw('DATE(tgl_beli) as tanggal'),
                'kd_supplier',
                'tbl_ikan.jenis_ikan',
                DB::raw('SUM(tbl_beli.jml_ikan) as total_kg')
            )
            ->join('tbl_ikan', 'tbl_beli.jenis_ikan', '=', 'tbl_ikan.id')
            ->with('supplier')
            ->whereBetween(DB::raw('DATE(tgl_beli)'), [
                $tanggalMulai->format('Y-m-d'),
                $hariIni->format('Y-m-d')
            ])
            ->groupBy('tanggal', 'kd_supplier', 'tbl_ikan.jenis_ikan')
            ->get();

        $colorMap = [
            'Ikan Gurame' => '#42a5f5',
            'Ikan Lele'   => '#66bb6a',
            'Ikan Mas'    => '#ffa726',
            'Ikan Mujair' => '#ab47bc',
        ];

        $jenisIkanGrouped = $supplierData->groupBy('jenis_ikan');
        $datasets = [];
        $supplierMapPerTanggal = [];
        $allSuppliers = [];

        foreach ($jenisIkanGrouped as $jenisIkan => $rows) {
            $data = [];
            foreach ($tanggalKeys as $tgl) {
                $totalHari = $rows->where('tanggal', $tgl)->sum('total_kg');
                $data[] = $totalHari;

                // Map supplier per tanggal
                $suppliersHari = $rows->where('tanggal', $tgl)
                    ->map(fn($r) => optional($r->supplier)->nm_supplier)
                    ->filter()
                    ->unique()
                    ->values()
                    ->toArray();

                $supplierMapPerTanggal[$tgl][$jenisIkan] = $suppliersHari;
                $allSuppliers = array_merge($allSuppliers, $suppliersHari);
            }

            $datasets[] = [
                'label' => $jenisIkan,
                'data' => $data,
                'backgroundColor' => $colorMap[$jenisIkan] ?? '#999'
            ];
        }

        $allSuppliers = array_values(array_unique($allSuppliers));


                // === 2. STOK per hari ===
                $ikanList = Ikan::with(['belis', 'detailJual'])->get();
                $stokChartData = [];
                foreach ($ikanList as $ikan) {
                    $dataPerHari = [];
                    foreach ($tanggalKeys as $tgl) {
                        $masuk = $ikan->belis()
                            ->whereDate('tgl_beli', '<=', $tgl)
                            ->sum('jml_ikan');
                        $keluar = $ikan->detailJual()
                            ->whereHas('jual', fn($q) => $q->whereDate('tgl_jual', '<=', $tgl))
                            ->sum('jml_ikan');
                        $stok = $masuk - $keluar;
                        $dataPerHari[] = $stok;
                    }
                    $stokChartData[] = [
                        'label' => $ikan->jenis_ikan,
                        'data'  => $dataPerHari
                    ];
                }

                // === 3. PASAR + KONSUMEN per hari ===
            $konsumenData = Jual::select(
                DB::raw('DATE(tbl_jual.tgl_jual) as tanggal'),
                'tbl_konsumen.nama_konsumen',
                'tbl_jual.nama_pasar',
                DB::raw('SUM(tbl_detail_jual.jml_ikan) as total_kg')
                )
                ->join('tbl_detail_jual', 'tbl_jual.jual_id', '=', 'tbl_detail_jual.jual_id')
                ->join('tbl_konsumen', 'tbl_jual.nama_konsumen', '=', 'tbl_konsumen.id') // sesuaikan jika FK beda
                ->whereBetween(DB::raw('DATE(tbl_jual.tgl_jual)'), [
                    $tanggalMulai->format('Y-m-d'),
                    $hariIni->format('Y-m-d')
                ])
                ->groupBy('tanggal', 'tbl_konsumen.nama_konsumen', 'tbl_jual.nama_pasar')
                ->get()
                ->groupBy(fn($item) => $item->nama_pasar.' - '.$item->nama_konsumen)
                ->map(function ($group) use ($tanggalKeys) {
                    return [
                        'label' => $group->first()->nama_pasar.' - '.$group->first()->nama_konsumen,
                        'data'  => collect($tanggalKeys)->map(fn($tgl) =>
                            $group->where('tanggal', $tgl)->sum('total_kg')
                        )->toArray()
                    ];
                })
                ->values();


                return view('pemilik.beranda', compact(
                    'tanggalLabels',
                    'tanggalKeys',
                    'datasets',
                    'stokChartData',
                    'konsumenData',
                    'supplierMapPerTanggal',
                    'allSuppliers'
                ));

    }
}
