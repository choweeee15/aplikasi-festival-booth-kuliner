<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
       Schema::create('kategori_kendaraan', function (Blueprint $table) {
    $table->id(); // Ini otomatis unsignedBigInteger
    $table->string('nama_kategori');
    $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('kategori_kendaraan');
    }
};
