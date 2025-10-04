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
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan'); // Nama layanan, misal: Ganti Oli
            $table->integer('harga'); // Harga layanan dalam satuan rupiah
            $table->string('estimasi'); // Estimasi waktu pengerjaan, misal: "1 jam"
            $table->enum('status', ['aktif', 'tidak aktif'])->default('aktif'); // Status layanan
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
