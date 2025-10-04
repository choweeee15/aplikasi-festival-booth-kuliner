<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pengunjungs', function (Blueprint $table) {
            // Jadikan password boleh null (opsional)
            // Jika kolom belum ada, HAPUS baris change() ini dan bikin kolom baru:
            // $table->string('password')->nullable()->after('no_hp');
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('pengunjungs', function (Blueprint $table) {
            // Kembalikan seperti semula (NOT NULL)
            $table->string('password')->nullable(false)->change();
        });
    }
};
