<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('pembelian_tiket')) {
            Schema::create('pembelian_tiket', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('pengunjung_id')->nullable()->index();
                $table->unsignedInteger('tiket_id')->nullable()->index();
                $table->integer('jumlah');
                $table->decimal('total_harga', 10, 2);
                $table->string('qr_code', 255)->nullable();
                $table->enum('status_bayar', ['pending','lunas'])->default('pending');
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('pengunjung_id')->references('id')->on('pengunjungs')->onDelete('set null');
                $table->foreign('tiket_id')->references('id')->on('tiket')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_tiket');
    }
};
