
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo_bulanan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('akun_id')
                ->constrained('akun')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedSmallInteger('tahun');
            $table->unsignedTinyInteger('bulan');

            $table->bigInteger('saldo_awal')->default(0);
            $table->unsignedBigInteger('total_masuk')->default(0);
            $table->unsignedBigInteger('total_keluar')->default(0);
            $table->bigInteger('saldo_akhir')->default(0);

            $table->timestamps();

            $table->unique(
                ['akun_id', 'tahun', 'bulan'],
                'saldo_bulanan_akun_periode_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo_bulanan');
    }
};
