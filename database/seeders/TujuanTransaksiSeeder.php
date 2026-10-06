<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TujuanTransaksi;

class TujuanTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TujuanTransaksi::insert([
            ['name' => 'Kas Kantor'],
            ['name' => 'AIRIN SH MH'],
            ['name' => 'APOTEK TAUFIQ MEDIKA'],
            ['name' => 'BONG MIE SIUN'],
            ['name' => 'BUDIANI ISTIKOMAH'],
            ['name' => 'CICALENGKA ELEKTRONIK'],
            ['name' => 'CYBERNET'],
            ['name' => 'DAMAI BAJA SELATAN'],
            ['name' => 'HILMAN SAPRUDIN'],
            ['name' => 'INDIHOME'],
            ['name' => 'INTAN COPY CENTRE'],
            ['name' => 'JOMANTARA FOTOCOPY'],
            ['name' => 'JUSTUS KIMIARAYA PT'],
            ['name' => 'KARYAWAN MCM'],
            ['name' => 'MEKAR LAKSANA'],
            ['name' => 'MOCH.SULTAN GANI'],
            ['name' => 'MULYA JAYA ELECTRICAL'],
            ['name' => 'NICESO'],
            ['name' => 'PIPIH FITRIAH'],
            ['name' => 'PLN'],
            ['name' => 'RAHMAT HIDAYAT'],
            ['name' => 'RINO FERNANDO'],
            ['name' => 'SERVIS BOR'],
            ['name' => 'SLAMET SASMITA'],
            ['name' => 'TB ARN'],
            ['name' => 'TB EKA JAYA PUTRA 4'],
            ['name' => 'TB MERDEKA'],
            ['name' => 'TB SEJAHTERA'],
            ['name' => 'TB SUMBER REZEKI'],
            ['name' => 'TOKO CAT ITRA SATU'],
            ['name' => 'TUNAI PAK AGUS'],
            ['name' => 'VIAN ALFIANA'],
            ['name' => 'TOKO SUMBER AIR MAS'],
        ]);
    }
}
