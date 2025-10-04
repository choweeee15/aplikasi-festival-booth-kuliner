<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyewas', function (Blueprint $table) {
            // Tambah kolom alamat kalau belum ada
            if (!Schema::hasColumn('penyewas', 'alamat')) {
                $table->string('alamat', 255)->nullable()->after('no_hp');
            }

            // Tambah timestamps kalau belum ada (biar Eloquent gak error)
            if (!Schema::hasColumn('penyewas', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('penyewas', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penyewas', function (Blueprint $table) {
            if (Schema::hasColumn('penyewas', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('penyewas', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('penyewas', 'alamat')) {
                $table->dropColumn('alamat');
            }
        });
    }
};
