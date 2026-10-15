<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;


use App\Models\TujuanTransaksi;
use App\Models\Transaksi;
use App\Models\Akun;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanCon extends Controller
{
    public function index(): View
    {
        $sumberDanaLaporan = \App\Models\Akun::whereIn('jenis_akun', ['Owner', 'Kas'])
            ->orderBy('name')
            ->get();

        $bulanAdaPerSumber = \App\Models\Akun::whereIn('jenis_akun', ['Owner', 'Kas'])
            ->get()
            ->mapWithKeys(function ($akun) {
                $transaksi = \App\Models\Transaksi::where(function ($query) use ($akun) {
                    $query->where('id_sumber_dana', $akun->id)
                        ->orWhere('id_tujuan_transaksi', $akun->id);
                })
                    ->selectRaw('YEAR(date) as tahun, MONTH(date) as bulan')
                    ->distinct()
                    ->orderBy('tahun', 'desc')
                    ->get()
                    ->groupBy('tahun')
                    ->map(function ($items) {
                        return $items->pluck('bulan')
                            ->map(fn($bulan) => (int) $bulan)
                            ->unique()
                            ->sort()
                            ->values()
                            ->toArray();
                    });

                return [$akun->id => $transaksi];
            });

       

        /* $data['sumberDanaLaporan'] = $sumberDanaLaporan;
         $data['bulanAdaPerSumber'] = $bulanAdaPerSumber;*/


        $bulanAdaPerTahun = \App\Models\Transaksi::whereHas('sumberDana', function ($query) {
            $query->where('jenis_akun', 'Owner');
        })
            ->selectRaw('YEAR(date) as tahun, MONTH(date) as bulan')
            ->distinct()
            ->get()
            ->groupBy('tahun')
            ->map(function ($transaksi) {
                return $transaksi->pluck('bulan')
                    ->map(fn($bulan) => (int) $bulan)
                    ->toArray();
            })
            ->sortKeysDesc();



        $data = [
            'title' => 'Laporan',
            'bulanAdaPerTahun' => $bulanAdaPerTahun,
            'sumberDanaLaporan' => $sumberDanaLaporan,
            'bulanAdaPerSumber' => $bulanAdaPerSumber,

        ];

        return view('laporan', compact('data'));
    }

    public function bos($tahun, $bulan)
    {
        /*$akunId = request()->query('akun');

        $transaksi = Transaksi::with(['sumberDana', 'tujuanTransaksi'])
            ->where(function ($query) use ($akunId) {
                $query->where('id_sumber_dana', $akunId)
                    ->orWhere('id_tujuan_transaksi', $akunId);
            })
            ->whereYear('date', $tahun)
            ->whereMonth('date', $bulan)
            ->orderBy('date')
            ->get();*/


        /*$akunId = request()->query('akun');

        $transaksi = Transaksi::with(['sumberDana', 'tujuanTransaksi'])
            ->where('id_sumber_dana', $akunId)
            ->whereYear('date', $tahun)
            ->whereMonth('date', $bulan)
            ->orderBy('date')
            ->get();*/
        /*$transaksi = Transaksi::with(['sumberDana', 'tujuanTransaksi'])->whereHas('sumberDana', function ($query) {
            $query->where('jenis_akun', 'Owner');
        })->whereYear('date', $tahun)->whereMonth('date', $bulan)->orderBy('date')->get();*/
        //$data = ['title' => 'Laporan Pengeluaran Bos', 'tahun' => $tahun, 'bulan' => $bulan, 'transaksi' => $transaksi, 'total' => $transaksi->sum('nominal'), 'namaBulan' => Carbon::createFromDate($tahun, $bulan, 1)->locale('id')->translatedFormat('F Y'),];

        $akunId = request()->query('akun');

        $akun = \App\Models\Akun::findOrFail($akunId);

        $transaksi = Transaksi::with(['sumberDana', 'tujuanTransaksi'])
            ->where(function ($query) use ($akunId) {
                $query->where('id_sumber_dana', $akunId)
                    ->orWhere('id_tujuan_transaksi', $akunId);
            })
            ->whereYear('date', $tahun)
            ->whereMonth('date', $bulan)
            ->orderBy('date')
            ->get();

        $saldoBerjalan = 0;

        $transaksi->each(function ($item) use ($akunId, &$saldoBerjalan) {
            if ($item->id_tujuan_transaksi == $akunId) {
                $saldoBerjalan += $item->nominal;
            }

            if ($item->id_sumber_dana == $akunId) {
                $saldoBerjalan -= $item->nominal;
            }

            $item->saldo_berjalan = $saldoBerjalan;
        });

        $data = [
            'title' => $akun->jenis_akun === 'Owner'
                ? 'Laporan Pengeluaran Bos'
                : 'Laporan Kas ' . $akun->name,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'namaBulan' => \Carbon\Carbon::createFromDate(
                $tahun,
                $bulan,
                1
            )->locale('id')->translatedFormat('F Y'),
            'akun' => $akun,
            'transaksi' => $transaksi,
            'totalMasuk' => $transaksi
                ->where('id_tujuan_transaksi', $akunId)
                ->sum('nominal'),
            'totalKeluar' => $transaksi
                ->where('id_sumber_dana', $akunId)
                ->sum('nominal'),
            'saldoAkhir' => $saldoBerjalan,
        ];

        $pdf = Pdf::loadView('laporan.pdf-bos', $data)->setPaper('a4', 'portrait');
        return $pdf->stream("Laporan-Pengeluaran-Bos-{$tahun}-{$bulan}.pdf");
    }

}
