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
use App\Exports\LaporanTransaksiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Collection;
use App\Models\SaldoBulanan;

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


    private function transaksiUntukPdf($tahun, $bulan)
    {
        abort_unless(
            is_numeric($tahun) &&
            (int) $tahun >= 2000 &&
            (int) $tahun <= 2100 &&
            is_numeric($bulan) &&
            (int) $bulan >= 1 &&
            (int) $bulan <= 12,
            404
        );

        $akun = Akun::findOrFail(request()->query('akun'));

        $transaksi = Transaksi::with(['sumberDana', 'tujuanTransaksi'])
            ->where(function ($query) use ($akun) {
                $query->where('id_sumber_dana', $akun->id)
                    ->orWhere('id_tujuan_transaksi', $akun->id);
            })
            ->whereYear('date', $tahun)
            ->whereMonth('date', $bulan)
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        // Ambil saldo bulanan khusus akun Kas
        $saldoBulanan = null;
        $saldoAwal = 0;

        if ($akun->jenis_akun === 'Kas') {
            $saldoBulanan = SaldoBulanan::where('akun_id', $akun->id)
                ->where('tahun', $tahun)
                ->where('bulan', $bulan)
                ->first();

            $saldoAwal = $saldoBulanan?->saldo_awal ?? 0;
        }

        // Saldo berjalan dimulai dari saldo awal bulan
        $saldoBerjalan = $saldoAwal;

        $transaksi->each(function ($item) use ($akun, &$saldoBerjalan) {
            if ($item->id_tujuan_transaksi == $akun->id) {
                $saldoBerjalan += $item->nominal;
            }

            if ($item->id_sumber_dana == $akun->id) {
                $saldoBerjalan -= $item->nominal;
            }

            $item->saldo_berjalan = $saldoBerjalan;
        });

        return [$akun, $transaksi, $saldoBulanan, $saldoAwal];
    }

    private function dataPdfTransaksi(
        $akun,
        $transaksi,
        $tahun,
        $bulan,
        $saldoBulanan,
        $saldoAwal
    ) {
        return [
            'title' => $akun->jenis_akun === 'Owner'
                ? 'Laporan Pengeluaran Bos'
                : 'Laporan Kas ' . $akun->name,

            'tahun' => $tahun,
            'bulan' => $bulan,

            'namaBulan' => Carbon::createFromDate(
                $tahun,
                $bulan,
                1
            )->locale('id')->translatedFormat('F Y'),

            'akun' => $akun,
            'transaksi' => $transaksi,

            'totalMasuk' => $transaksi
                ->where('id_tujuan_transaksi', $akun->id)
                ->sum('nominal'),

            'totalKeluar' => $transaksi
                ->where('id_sumber_dana', $akun->id)
                ->sum('nominal'),

            'saldoAwal' => $saldoAwal,

            'saldoAkhir' => $akun->jenis_akun === 'Kas' && $saldoBulanan
                ? $saldoBulanan->saldo_akhir
                : ($transaksi->isNotEmpty()
                    ? $transaksi->last()->saldo_berjalan
                    : 0),
        ];
    }

    public function exportPdfTransaksi($tahun, $bulan)
    {
        [$akun, $transaksi, $saldoBulanan, $saldoAwal] = $this->transaksiUntukPdf(
            $tahun,
            $bulan
        );

        if ($transaksi->isEmpty()) {
            return back()->withErrors([
                'pdf' => 'Tidak ada transaksi pada periode ini.',
            ]);
        }

        $data = $this->dataPdfTransaksi(
            $akun,
            $transaksi,
            $tahun,
            $bulan,
            $saldoBulanan,
            $saldoAwal
        );

        $pdf = Pdf::loadView('laporan.pdf-bos', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream(
            "Laporan-Transaksi-{$bulan}-{$tahun}.pdf"
        );
    }

    private function lampiranUntukPdf($tahun, $bulan)
    {
        abort_unless(
            is_numeric($tahun) &&
            (int) $tahun >= 2000 &&
            (int) $tahun <= 2100 &&
            is_numeric($bulan) &&
            (int) $bulan >= 1 &&
            (int) $bulan <= 12,
            404
        );

        $akun = Akun::findOrFail(request()->query('akun'));

        $transaksi = Transaksi::where(function ($query) use ($akun) {
            $query->where('id_sumber_dana', $akun->id)
                ->orWhere('id_tujuan_transaksi', $akun->id);
        })
            ->whereYear('date', $tahun)
            ->whereMonth('date', $bulan)
            ->where(function ($query) {
                $query->whereNotNull('bukti')
                    ->orWhereNotNull('bukti_nota');
            })
            ->orderBy('date')
            ->orderBy('id')
            ->get()
            ->filter(function ($item) {
                return (
                    $item->bukti &&
                    Storage::disk('public')->exists($item->bukti)
                ) || (
                    $item->bukti_nota &&
                    Storage::disk('public')->exists($item->bukti_nota)
                );
            })
            ->values();

        return [$akun, $transaksi];
    }

    public function indexPdfLampiran($tahun, $bulan)
    {
        [$akun, $transaksi] = $this->lampiranUntukPdf(
            $tahun,
            $bulan
        );

        if ($transaksi->isEmpty()) {
            return back()->withErrors([
                'pdf' => 'Tidak ada file lampiran pada periode ini.',
            ]);
        }

        $jumlahPart = (int) ceil($transaksi->count() / 40);

        return view('laporan.pdf-part-index', [
            'title' => 'PDF Lampiran',
            'jenis' => 'lampiran',
            'akun' => $akun,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'namaBulan' => Carbon::createFromDate(
                $tahun,
                $bulan,
                1
            )->locale('id')->translatedFormat('F Y'),
            'jumlahPart' => $jumlahPart,
        ]);
    }

    public function exportPdfLampiran($tahun, $bulan, $part)
    {
        abort_unless(
            is_numeric($part) && (int) $part >= 1,
            404
        );

        [$akun, $transaksi] = $this->lampiranUntukPdf(
            $tahun,
            $bulan
        );

        $bagian = $transaksi->chunk(40)->values();

        $index = (int) $part - 1;

        abort_unless(isset($bagian[$index]), 404);

        $data = [
            'title' => $akun->jenis_akun === 'Owner'
                ? 'Lampiran Pengeluaran Bos'
                : 'Lampiran Kas ' . $akun->name,

            'tahun' => $tahun,
            'bulan' => $bulan,

            'namaBulan' => Carbon::createFromDate(
                $tahun,
                $bulan,
                1
            )->locale('id')->translatedFormat('F Y'),

            'akun' => $akun,
            'transaksi' => $bagian[$index],
        ];

        $pdf = Pdf::loadView('laporan.lampiran-pdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream(
            "Laporan-Lampiran-{$bulan}-{$tahun}-Part-{$part}.pdf"
        );
    }


    private function transaksiUntukExport($tahun, $bulan)
    {
        $request = request();

        abort_unless(
            is_numeric($tahun) &&
            (int) $tahun >= 2000 &&
            (int) $tahun <= 2100 &&
            is_numeric($bulan) &&
            (int) $bulan >= 1 &&
            (int) $bulan <= 12,
            404
        );

        $akun = Akun::findOrFail($request->query('akun'));

        $transaksi = Transaksi::with(['sumberDana', 'tujuanTransaksi'])
            ->where(function ($query) use ($akun) {
                $query->where('id_sumber_dana', $akun->id)
                    ->orWhere('id_tujuan_transaksi', $akun->id);
            })
            ->whereYear('date', $tahun)
            ->whereMonth('date', $bulan)
            ->orderBy('date')
            ->get();

        return [$akun, $transaksi];
    }

    public function exportExcel($tahun, $bulan)
    {
        [$akun, $transaksi] = $this->transaksiUntukExport(
            $tahun,
            $bulan
        );

        /*$rows = $transaksi->map(function ($item) {
            return [
                Carbon::parse($item->date)->format('d/m/Y'),
                (string) $item->kode_bukti,
                $item->sumberDana?->name ?? '',
                $item->tujuanTransaksi?->name ?? '',
                (int) $item->nominal,
                $item->keterangan ?? '',
                $item->bukti ? basename($item->bukti) : '',
                $item->bukti_nota ? basename($item->bukti_nota) : '',
            ];
        })->toArray();*/
        $rows = $transaksi->map(function ($item) {
            $kode = (string) $item->kode_bukti;

            $extTransfer = $item->bukti
                ? strtolower(pathinfo($item->bukti, PATHINFO_EXTENSION))
                : '';

            $extNota = $item->bukti_nota
                ? strtolower(pathinfo($item->bukti_nota, PATHINFO_EXTENSION))
                : '';

            return [
                Carbon::parse($item->date)->format('d/m/Y'),
                $kode,
                $item->sumberDana?->name ?? '',
                $item->tujuanTransaksi?->name ?? '',
                (int) $item->nominal,
                $item->keterangan ?? '',
                $extTransfer ? $kode . '.' . $extTransfer : '',
                $extNota ? $kode . '_nota.' . $extNota : '',
            ];
        })->toArray();

        $namaFile = "transaksi_{$bulan}_{$tahun}.xlsx";

        return Excel::download(
            new LaporanTransaksiExport($rows),
            $namaFile
        );
    }

    public function exportZip($tahun, $bulan)
    {
        [$akun, $transaksi] = $this->transaksiUntukExport(
            $tahun,
            $bulan
        );

        $zipPath = tempnam(sys_get_temp_dir(), 'bukti_');

        if ($zipPath === false) {
            throw new \RuntimeException('Gagal membuat file ZIP sementara.');
        }

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            @unlink($zipPath);

            throw new \RuntimeException('Gagal membuat file ZIP.');
        }

        $jumlahFile = 0;

        try {
            foreach ($transaksi as $item) {
                $kode = trim((string) $item->kode_bukti);

                if ($kode === '') {
                    continue;
                }

                // Bukti transfer
                if ($item->bukti) {
                    $path = $item->bukti;

                    if (Storage::disk('public')->exists($path)) {
                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                        $namaZip = $kode . '.' . $ext;

                        if (
                            $zip->addFromString(
                                $namaZip,
                                Storage::disk('public')->get($path)
                            )
                        ) {
                            $jumlahFile++;
                        }
                    }
                }

                // Bukti nota
                if ($item->bukti_nota) {
                    $path = $item->bukti_nota;

                    if (Storage::disk('public')->exists($path)) {
                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

                        $namaZip = $kode . '_nota.' . $ext;

                        if (
                            $zip->addFromString(
                                $namaZip,
                                Storage::disk('public')->get($path)
                            )
                        ) {
                            $jumlahFile++;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            $zip->close();
            @unlink($zipPath);

            throw $e;
        }

        $zip->close();

        if ($jumlahFile === 0) {
            @unlink($zipPath);

            return back()->withErrors([
                'file_bukti' => 'Tidak ada file bukti yang ditemukan untuk periode ini.',
            ]);
        }

        return response()
            ->download($zipPath, "bukti_transaksi_{$bulan}_{$tahun}.zip")
            ->deleteFileAfterSend(true);
    }

}
