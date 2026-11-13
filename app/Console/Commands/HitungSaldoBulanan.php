<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Akun;
use App\Models\Transaksi;
use App\Services\SaldoBulananService;

class HitungSaldoBulanan extends Command
{
    protected $signature = 'saldo:hitung-ulang';

    protected $description = 'Menghitung ulang seluruh saldo bulanan akun Kas';

    public function handle(SaldoBulananService $saldoService): int
    {
        $akunKas = Akun::where('jenis_akun', 'Kas')->get();

        foreach ($akunKas as $akun) {
            $tanggalPertama = Transaksi::where(function ($query) use ($akun) {
                $query->where('id_sumber_dana', $akun->id)
                    ->orWhere('id_tujuan_transaksi', $akun->id);
            })->min('date');

            $saldoService->hitungUlang(
                $akun->id,
                $tanggalPertama ?? now()
            );

            $this->info("Saldo akun {$akun->name} berhasil dihitung.");
        }

        $this->info('Seluruh saldo bulanan berhasil diperbarui.');

        return self::SUCCESS;
    }
}
