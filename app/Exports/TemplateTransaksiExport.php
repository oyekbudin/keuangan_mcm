<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TemplateTransaksiExport implements FromArray, WithHeadings, WithColumnFormatting
{
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
        return [
            [
                '01/10/2026',
                'BPU001',
                'Kas Kantor',
                'Toko Bangunan',
                500000,
                'Pembelian material',
                'TF001.jpg',
                'NT001.jpg',
            ],
            [
                '02/10/2026',
                '0001',
                'Owner',
                'Kas Kantor',
                2000000,
                'Penambahan kas',
                'transfer_02.png',
                '',
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }
}