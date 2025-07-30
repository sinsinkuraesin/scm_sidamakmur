<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beli;
use App\Models\DetailJual;
use App\Models\Ikan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanPersediaanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal');
        $jenisIkanFilter = $request->get('jenis_ikan');

        $daftarJenisIkan = Ikan::pluck('jenis_ikan')->sort()->values();

        $laporanStok = [];

        // Tampilkan data hanya jika tanggal dipilih
        if ($tanggal !== null) {
            $laporanStok = $this->getStokData($tanggal, $jenisIkanFilter);
        }

        return view('admin.laporan.persediaan', compact('laporanStok', 'daftarJenisIkan', 'tanggal'));
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

        return $pdf->stream('laporan_persediaan_' . $tanggal . '.pdf');
    }

    private function getStokData($tanggal, $jenisIkan)
    {
        $daftarJenisIkan = Ikan::pluck('jenis_ikan');

        $laporanStok = [];
        // Jika filter jenis ikan kosong atau null → semua ikan
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
                ->whereHas('jual', function ($query) use ($tanggal) {
                    $query->whereDate('tgl_jual', $tanggal);
                })
                ->sum('jml_ikan');

            $stokAkhir = $stokAwal + $masuk - $keluar;

            $laporanStok[] = [
                'tanggal' => $tanggal,
                'jenis_ikan' => $jenis_ikan,
                'stok_awal' => $stokAwal,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'stok_akhir' => $stokAkhir,
            ];
        }

        return $laporanStok;
    }

    private function hitungStokSebelumnya($idIkan, $tanggal)
    {
        $totalMasuk = Beli::where('jenis_ikan', $idIkan)
            ->whereDate('tgl_beli', '<', $tanggal)
            ->sum('jml_ikan');

        $totalKeluar = DetailJual::where('jenis_ikan', $idIkan)
            ->whereHas('jual', function ($query) use ($tanggal) {
                $query->whereDate('tgl_jual', '<', $tanggal);
            })
            ->sum('jml_ikan');

        return $totalMasuk - $totalKeluar;
    }
}
