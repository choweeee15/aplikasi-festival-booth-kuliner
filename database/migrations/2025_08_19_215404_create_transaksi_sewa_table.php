<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('transaksi_sewa')) {
            Schema::create('transaksi_sewa', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('penyewa_id')->nullable()->index();
                $table->unsignedInteger('booth_id')->nullable()->index();
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                $table->decimal('total_bayar', 10, 2);
                $table->enum('status', ['pending','disetujui','ditolak'])->default('pending');
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('penyewa_id')->references('id')->on('penyewas')->onDelete('set null');
                $table->foreign('booth_id')->references('id')->on('booths')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_sewa');
    }
};
