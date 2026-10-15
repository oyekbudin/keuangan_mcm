<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LaporanTransaksiExport implements FromArray, WithHeadings, WithColumnFormatting
{
    protected array $transaksi;

    public function __construct(array $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode Bukti',
            'Sumber Dana',
            'Tujuan',
            'Nominal',
            'Keterangan',
            'File Transfer',
            'File Nota',
        ];
    }

    public function array(): array
    {
        return $this->transaksi;
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }
}