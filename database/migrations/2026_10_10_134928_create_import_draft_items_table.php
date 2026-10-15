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
        Schema::create('import_draft_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('import_draft_id')
                ->constrained('import_drafts')
                ->cascadeOnDelete();

            $table->unsignedInteger('row_number');
            $table->date('tanggal')->nullable();
            $table->string('kode_bukti')->nullable();

            $table->string('sumber_dana')->nullable();
            $table->unsignedBigInteger('id_sumber_dana')->nullable();

            $table->string('tujuan')->nullable();
            $table->unsignedBigInteger('id_tujuan_transaksi')->nullable();

            $table->unsignedBigInteger('nominal')->nullable();
            $table->text('keterangan')->nullable();

            $table->string('file_transfer')->nullable();
            $table->string('file_nota')->nullable();

            $table->boolean('is_checked')->default(false);
            $table->json('errors')->nullable();

            $table->timestamps();

            $table->index(['import_draft_id', 'tanggal']);
            $table->index(['import_draft_id', 'is_checked']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_draft_items');
    }
};
