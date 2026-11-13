<?php

namespace App\Observers;

use App\Models\Transaksi;
use App\Services\SaldoBulananService;
use Carbon\Carbon;

class TransaksiObserver
{
    protected $saldoService;

    public function __construct(SaldoBulananService $saldoService)
    {
        $this->saldoService = $saldoService;
    }

    public function created(Transaksi $transaksi): void
    {
        $this->perbaruiSaldo([
            $transaksi->getAttributes(),
        ]);
    }

    public function updating(Transaksi $transaksi): void
    {
        if (!$transaksi->isDirty([
            'date',
            'id_sumber_dana',
            'id_tujuan_transaksi',
            'nominal',
        ])) {
            return;
        }

        $transaksi->setRelation('_saldo_sebelum_update', [
            'date' => $transaksi->getRawOriginal('date'),
            'id_sumber_dana' => $transaksi->getRawOriginal('id_sumber_dana'),
            'id_tujuan_transaksi' => $transaksi->getRawOriginal('id_tujuan_transaksi'),
        ]);
    }

    public function updated(Transaksi $transaksi): void
    {
        if (!$transaksi->relationLoaded('_saldo_sebelum_update')) {
            return;
        }

        $sebelum = $transaksi->getRelation('_saldo_sebelum_update');

        $transaksi->unsetRelation('_saldo_sebelum_update');

        $this->perbaruiSaldo([
            $sebelum,
            $transaksi->getAttributes(),
        ]);
    }

    public function deleted(Transaksi $transaksi): void
    {
        $this->perbaruiSaldo([
            $transaksi->getAttributes(),
        ]);
    }

    private function perbaruiSaldo(array $transaksiList): void
    {
        $akunTerdampak = [];

        foreach ($transaksiList as $transaksi) {
            $tanggal = $transaksi['date'];

            foreach ([
                $transaksi['id_sumber_dana'],
                $transaksi['id_tujuan_transaksi'],
            ] as $akunId) {

                if (!$akunId) {
                    continue;
                }

                $tanggal = Carbon::parse($tanggal)->startOfMonth();

                if (
                    !isset($akunTerdampak[$akunId]) ||
                    $tanggal->lt($akunTerdampak[$akunId])
                ) {
                    $akunTerdampak[$akunId] = $tanggal;
                }
            }
        }

        foreach ($akunTerdampak as $akunId => $tanggal) {
            $this->saldoService->hitungUlang(
                (int) $akunId,
                $tanggal
            );
        }
    }
}
