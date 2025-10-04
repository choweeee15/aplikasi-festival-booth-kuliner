<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->unsignedBigInteger('layanan_id')->nullable();
            $table->unsignedBigInteger('mekanik_id')->nullable();
            $table->string('tipe_kendaraan', 50)->nullable();
            $table->string('nama_kendaraan', 100)->nullable();
            $table->string('model_kendaraan', 100)->nullable();
            $table->string('nama_pemilik', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('alamat')->nullable();
            $table->enum('jenis_permintaan', ['dropoff', 'pickup'])->nullable();
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'diproses', 'selesai', 'ditolak'])->default('menunggu');
            $table->decimal('harga_ditentukan', 10, 2)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('kategori_id')->references('id')->on('kategori_kendaraan')->onDelete('set null');
            $table->foreign('layanan_id')->references('id')->on('layanan')->onDelete('set null');
            $table->foreign('mekanik_id')->references('id')->on('mekanik')->onDelete('set null');
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrasi');
    }
};
