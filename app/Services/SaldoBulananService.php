<?php

namespace App\Services;

use App\Models\Akun;
use App\Models\SaldoBulanan;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaldoBulananService
{
    public function hitungUlang(int $akunId, $mulaiDari): void
    {
        DB::transaction(function () use ($akunId, $mulaiDari) {

            $akun = Akun::find($akunId);

            // Saldo bulanan hanya untuk akun Kas
            if (!$akun || $akun->jenis_akun !== 'Kas') {
                return;
            }

            // Cari transaksi paling awal dan paling akhir
            $query = Transaksi::where(function ($q) use ($akunId) {
                $q->where('id_sumber_dana', $akunId)
                    ->orWhere('id_tujuan_transaksi', $akunId);
            });

            $tanggalPertama = (clone $query)->min('date');
            $tanggalTerakhir = (clone $query)->max('date');

            // Jika akun sudah tidak memiliki transaksi
            if (!$tanggalPertama) {
                SaldoBulanan::where('akun_id', $akunId)->delete();
                return;
            }

            $bulanPertama = Carbon::parse($tanggalPertama)->startOfMonth();
            $bulanTerakhir = Carbon::parse($tanggalTerakhir)->startOfMonth();

            $bulanMulai = Carbon::parse($mulaiDari)->startOfMonth();

            if ($bulanMulai->lt($bulanPertama)) {
                $bulanMulai = $bulanPertama->copy();
            }

            // Hapus catatan saldo sebelum transaksi paling awal
            SaldoBulanan::where('akun_id', $akunId)
                ->where(function ($q) use ($bulanPertama) {
                    $q->where('tahun', '<', $bulanPertama->year)
                        ->orWhere(function ($q2) use ($bulanPertama) {
                            $q2->where('tahun', $bulanPertama->year)
                                ->where('bulan', '<', $bulanPertama->month);
                        });
                })
                ->delete();

            // Jika bulan mulai sudah melewati transaksi terakhir
            if ($bulanMulai->gt($bulanTerakhir)) {
                SaldoBulanan::where('akun_id', $akunId)
                    ->where(function ($q) use ($bulanMulai) {
                        $q->where('tahun', '>', $bulanMulai->year)
                            ->orWhere(function ($q2) use ($bulanMulai) {
                                $q2->where('tahun', $bulanMulai->year)
                                    ->where('bulan', '>=', $bulanMulai->month);
                            });
                    })
                    ->delete();

                return;
            }

            // Ambil saldo akhir bulan sebelumnya
            $bulanSebelumnya = $bulanMulai->copy()->subMonth();

            $saldoSebelumnya = SaldoBulanan::where('akun_id', $akunId)
                ->where('tahun', $bulanSebelumnya->year)
                ->where('bulan', $bulanSebelumnya->month)
                ->first();

            // Jika data saldo sebelumnya belum tersedia,
            // hitung ulang dari bulan transaksi pertama
            if ($bulanMulai->gt($bulanPertama) && !$saldoSebelumnya) {
                $bulanMulai = $bulanPertama->copy();
            }

            $saldoAwal = $bulanMulai->gt($bulanPertama)
                ? $saldoSebelumnya->saldo_akhir
                : 0;

            // Hitung setiap bulan sampai transaksi terakhir
            $bulan = $bulanMulai->copy();

            while ($bulan->lte($bulanTerakhir)) {

                $awalBulan = $bulan->copy()->startOfMonth();
                $akhirBulan = $bulan->copy()->endOfMonth();

                $masuk = Transaksi::where(
                    'id_tujuan_transaksi',
                    $akunId
                )
                    ->whereBetween('date', [$awalBulan, $akhirBulan])
                    ->sum('nominal');

                $keluar = Transaksi::where(
                    'id_sumber_dana',
                    $akunId
                )
                    ->whereBetween('date', [$awalBulan, $akhirBulan])
                    ->sum('nominal');

                $saldoAkhir = $saldoAwal + $masuk - $keluar;

                SaldoBulanan::updateOrCreate(
                    [
                        'akun_id' => $akunId,
                        'tahun' => $bulan->year,
                        'bulan' => $bulan->month,
                    ],
                    [
                        'saldo_awal' => $saldoAwal,
                        'total_masuk' => $masuk,
                        'total_keluar' => $keluar,
                        'saldo_akhir' => $saldoAkhir,
                    ]
                );

                $saldoAwal = $saldoAkhir;

                $bulan->addMonth();
            }

            // Hapus catatan saldo setelah transaksi terakhir
            SaldoBulanan::where('akun_id', $akunId)
                ->where(function ($q) use ($bulanTerakhir) {
                    $q->where('tahun', '>', $bulanTerakhir->year)
                        ->orWhere(function ($q2) use ($bulanTerakhir) {
                            $q2->where('tahun', $bulanTerakhir->year)
                                ->where('bulan', '>', $bulanTerakhir->month);
                        });
                })
                ->delete();
        });
    }
}
