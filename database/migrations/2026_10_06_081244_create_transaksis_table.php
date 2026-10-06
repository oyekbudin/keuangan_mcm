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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bukti', 20);
            $table->timestamp('date');

            $table->foreignId('id_sumber_dana')
                ->constrained('sumber_dana')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('id_tujuan_transaksi')
                ->constrained('tujuan_transaksi')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->integer('nominal');
            $table->string('keterangan');
            $table->text('bukti')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
