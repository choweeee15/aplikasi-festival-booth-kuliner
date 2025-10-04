<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengunjungs', function (Blueprint $table) {
            // tambahkan kolom timestamps hanya jika belum ada
            if (!Schema::hasColumn('pengunjungs', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('pengunjungs', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengunjungs', function (Blueprint $table) {
            if (Schema::hasColumn('pengunjungs', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('pengunjungs', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });
    }
};
