<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permintaan_id')->nullable();
            $table->integer('jumlah_tagihan')->nullable();
            $table->text('rincian_biaya')->nullable();          // kolom tambahan
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->enum('status', ['menunggu', 'diverifikasi', 'ditolak'])->default('menunggu');
            $table->timestamp('tanggal_upload')->useCurrent();
            $table->timestamp('tanggal_verifikasi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->foreign('permintaan_id')->references('id')->on('permintaan_servis')->onDelete('set null');
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
