<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pastikan tabel users ada
        // Ubah kolom role menjadi VARCHAR(20) NOT NULL DEFAULT 'user'
        DB::statement("ALTER TABLE `users` MODIFY `role` VARCHAR(20) NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        // Jika sebelumnya enum('admin','user'), kamu bisa rollback ke enum:
        // DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','user') NOT NULL DEFAULT 'user'");

        // Atau kalau sebelumnya tinyint(1):
        // DB::statement("ALTER TABLE `users` MODIFY `role` TINYINT(1) NOT NULL DEFAULT 0");

        // Supaya aman (tidak mengasumsikan tipe lama), kita kembalikan ke ENUM:
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }
};
