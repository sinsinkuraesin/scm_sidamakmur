<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beli;
use App\Models\DetailJual;
use App\Models\Ikan;
use Barryvdh\DomPDF\Facade\Pdf;

class PemilikLaporanStokController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal');
        $jenisIkanFilter = $request->get('jenis_ikan');
        $daftarJenisIkan = Ikan::pluck('jenis_ikan')->sort()->values();
        $laporanStok = [];

        if ($tanggal) {
            $laporanStok = $this->getStokData($tanggal, $jenisIkanFilter);
        }

        return view('pemilik.lap_stok', compact('laporanStok', 'daftarJenisIkan', 'tanggal', 'jenisIkanFilter'));
    }

    public function exportPdf(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->format('Y-m-d'));
        $jenisIkan = $request->input('jenis_ikan', '');

        $laporanStok = $this->getStokData($tanggal, $jenisIkan);

        $pdf = Pdf::loadView('admin.laporan.persediaan_pdf', [
            'tanggal' => $tanggal,
            'laporanStok' => $laporanStok,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan_stok_' . $tanggal . '.pdf');
    }

    private function getStokData($tanggal, $jenisIkan)
    {
        $daftarJenisIkan = Ikan::pluck('jenis_ikan');
        $laporanStok = [];
        $jenisYangDiproses = ($jenisIkan !== null && $jenisIkan !== '') ? [$jenisIkan] : $daftarJenisIkan;

        foreach ($jenisYangDiproses as $jenis_ikan) {
            $ikan = Ikan::where('jenis_ikan', $jenis_ikan)->first();
            if (!$ikan) continue;

            $idIkan = $ikan->id;
            $stokAwal = $this->hitungStokSebelumnya($idIkan, $tanggal);

            $masuk = Beli::where('jenis_ikan', $idIkan)
                ->whereDate('tgl_beli', $tanggal)
                ->sum('jml_ikan');

            $keluar = DetailJual::where('jenis_ikan', $idIkan)
                ->whereHas('jual', fn ($q) => $q->whereDate('tgl_jual', $tanggal))
                ->sum('jml_ikan');

            $laporanStok[] = [
                'tanggal' => $tanggal,
                'jenis_ikan' => $jenis_ikan,
                'stok_awal' => $stokAwal,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'stok_akhir' => $stokAwal + $masuk - $keluar,
            ];
        }

        return $laporanStok;
    }

    private function hitungStokSebelumnya($idIkan, $tanggal)
    {
        $masuk = Beli::where('jenis_ikan', $idIkan)->whereDate('tgl_beli', '<', $tanggal)->sum('jml_ikan');
        $keluar = DetailJual::where('jenis_ikan', $idIkan)
            ->whereHas('jual', fn ($q) => $q->whereDate('tgl_jual', '<', $tanggal))
            ->sum('jml_ikan');

        return $masuk - $keluar;
    }
}
