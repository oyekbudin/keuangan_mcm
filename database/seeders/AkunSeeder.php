<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AkunSeeder extends Seeder
{
    public function run(): void
    {
        $akun = [
            [1, 'Bos', 'Owner'],
            [2, 'Kas Kantor', 'Kas'],
            [3, 'TB ARN', 'Toko'],
            [4, 'TB EKA JAYA PUTRA 4', 'Toko'],
            [5, 'TB MERDEKA', 'Toko'],
            [6, 'TB SEJAHTERA', 'Toko'],
            [7, 'TB SUMBER REZEKI', 'Toko'],
            [8, 'BONG MIE SIUN', 'Toko'],
            [9, 'CICALENGKA ELEKTRONIK', 'Toko'],
            [10, 'CYBERNET', 'Toko'],
            [11, 'DAMAI BAJA SELATAN', 'Toko'],
            [12, 'INDIHOME', 'Toko'],
            [13, 'INTAN COPY CENTRE', 'Toko'],
            [14, 'JOMANTARA FOTOCOPY', 'Toko'],
            [15, 'JUSTUS KIMIARAYA PT', 'Toko'],
            [16, 'MEKAR LAKSANA', 'Toko'],
            [17, 'MULYA JAYA ELECTRICAL', 'Toko'],
            [18, 'NICESO', 'Toko'],
            [19, 'PLN', 'Toko'],
            [20, 'SERVIS BOR', 'Toko'],
            [21, 'TOKO CAT ITRA SATU', 'Toko'],
            [22, 'TOKO SUMBER AIR MAS', 'Toko'],
            [23, 'APOTEK TAUFIQ MEDIKA', 'Toko'],
            [24, 'AIRIN SH MH', 'Perorangan'],
            [25, 'BUDIANI ISTIKOMAH', 'Perorangan'],
            [26, 'HILMAN SAPRUDIN', 'Perorangan'],
            [27, 'KARYAWAN MCM', 'Perorangan'],
            [28, 'MOCH.SULTAN GANI', 'Perorangan'],
            [29, 'PIPIH FITRIAH', 'Perorangan'],
            [30, 'RAHMAT HIDAYAT', 'Perorangan'],
            [31, 'RINO FERNANDO', 'Perorangan'],
            [32, 'SLAMET SASMITA', 'Toko'],
            [33, 'TUNAI PAK AGUS', 'Perorangan'],
            [34, 'VIAN ALFIANA', 'Perorangan'],
            [35, 'Kas MCM Listrik', 'Kas'],
            [36, 'Transportasi', 'Perorangan'],
            [37, 'Warung Sembako', 'Toko'],
            [38, 'Lapangan Futsal', 'Toko'],
            [39, 'Service Elektronik', 'Toko'],
            [40, 'Percetakan', 'Toko'],
            [41, 'Konter', 'Toko'],
            [42, 'Warung Makan', 'Toko'],
            [43, 'POM', 'Toko'],
            [44, 'Toko Perabot', 'Toko'],
            [45, 'Bengkel', 'Toko'],
            [46, 'Toko Hewan', 'Toko'],
            [47, 'Indomaret', 'Toko'],
            [48, 'Admin Bank', 'Toko'],
        ];

        DB::transaction(function () use ($akun) {
            foreach ($akun as [$id, $name, $jenisAkun]) {
                DB::table('akun')->updateOrInsert(
                    ['id' => $id],
                    [
                        'name' => $name,
                        'jenis_akun' => $jenisAkun,
                    ]
                );
            }
        });
    }
}
