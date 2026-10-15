<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    public function up(): void
    {
        Schema::create('pemetaan_uraian_tujuan', function (Blueprint $table) {
            $table->id();
            $table->text('uraian');
            $table->char('uraian_hash', 64)->unique();
            $table->foreignId('id_tujuan_transaksi')
                ->nullable()
                ->constrained('akun')
                ->nullOnDelete();
            $table->timestamps();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemetaan_uraian_tujuan');
    }
};
