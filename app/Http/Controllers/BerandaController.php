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

class BerandaController extends Controller
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
            'Ikan Mujaer' => '#ab47bc',
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
            'Ikan Mujaer' => '#ab47bc',
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

                // === 2. STOK PERHARI REAL-TIME ===
                $ikanList = Ikan::all();
                $stokChartData = [];

                foreach ($ikanList as $ikan) {
                    $dataPerHari = [];

                    // Ambil stok terakhir dari tbl_ikan
                    $stokHariTerakhir = $ikan->stok;

                    // Simpan stok terakhir ke array
                    $stokPerTanggal = [];
                    $stokPerTanggal[$tanggalKeys->last()] = $stokHariTerakhir;

                    // Hitung mundur ke hari sebelumnya
                    for ($i = count($tanggalKeys) - 2; $i >= 0; $i--) {
                        $tglSekarang = $tanggalKeys[$i];
                        $tglBesok    = $tanggalKeys[$i + 1];

                        // Ambil data pembelian & penjualan di tglBesok
                        $masukBesok = Beli::where('jenis_ikan', $ikan->id)
                            ->whereDate('tgl_beli', $tglBesok)
                            ->sum('jml_ikan');

                        $keluarBesok = DetailJual::where('jenis_ikan', $ikan->id)
                            ->whereHas('jual', function ($q) use ($tglBesok) {
                                $q->whereDate('tgl_jual', $tglBesok);
                            })
                            ->sum('jml_ikan');

                        // Hitung stok mundur
                        $stokPerTanggal[$tglSekarang] = $stokPerTanggal[$tglBesok] - $masukBesok + $keluarBesok;
                    }

                    // Urutkan stok sesuai tanggalKeys
                    foreach ($tanggalKeys as $tgl) {
                        $dataPerHari[] = $stokPerTanggal[$tgl] ?? 0;
                    }

                    $stokChartData[] = [
                        'label' => $ikan->jenis_ikan,
                        'data'  => $dataPerHari
                    ];
                }


                // === 3. PASAR + KONSUMEN per hari ===
            // === 3. DISTRIBUSI IKAN KE PASAR ===
            $pasarData = Jual::select(
                    DB::raw('DATE(tbl_jual.tgl_jual) as tanggal'),
                    'tbl_ikan.jenis_ikan',
                    'tbl_jual.nama_pasar',
                    DB::raw('SUM(tbl_detail_jual.jml_ikan) as total_kg')
                )
                ->join('tbl_detail_jual', 'tbl_jual.jual_id', '=', 'tbl_detail_jual.jual_id')
                ->join('tbl_ikan', 'tbl_detail_jual.jenis_ikan', '=', 'tbl_ikan.id')
                ->whereBetween(DB::raw('DATE(tbl_jual.tgl_jual)'), [
                    $tanggalMulai->format('Y-m-d'),
                    $hariIni->format('Y-m-d')
                ])
                ->groupBy('tanggal', 'jenis_ikan', 'nama_pasar')
                ->get();

            // Untuk chart utama: dataset jenis ikan per tanggal
            $ikanGrouped = $pasarData->groupBy('jenis_ikan');
            $ikanPasarMap = [];
            $datasets = [];

            foreach ($ikanGrouped as $jenisIkan => $rows) {
                $data = [];

                foreach ($tanggalKeys as $tgl) {
                    $totalKg = $rows->where('tanggal', $tgl)->sum('total_kg');
                    $data[] = $totalKg;

                    // Buat tooltip mapping
                    $pasarList = $rows->where('tanggal', $tgl)
                        ->groupBy('nama_pasar')
                        ->map(fn($g) => $g->sum('total_kg'))
                        ->toArray();

                    $ikanPasarMap[$tgl][$jenisIkan] = $pasarList;
                }

                $datasets[] = [
                    'label' => $jenisIkan,
                    'data' => $data,
                    'backgroundColor' => $colorMap[$jenisIkan] ?? '#999'
                ];
            }

                return view('admin.beranda', compact(
                    'tanggalLabels',
                    'tanggalKeys',
                    'datasets',
                    'stokChartData',
                    'supplierMapPerTanggal',
                    'allSuppliers',
                    'ikanPasarMap',

                ));

    }
}
