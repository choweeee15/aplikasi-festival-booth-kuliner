<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pembelian_tiket', function (Blueprint $table) {
            if (!Schema::hasColumn('pembelian_tiket','checked_in_at')) {
                $table->timestamp('checked_in_at')->nullable()->after('status_bayar');
            }
            if (!Schema::hasColumn('pembelian_tiket','checked_in_by')) {
                $table->unsignedBigInteger('checked_in_by')->nullable()->after('checked_in_at');
            }
            if (!Schema::hasColumn('pembelian_tiket','scan_count')) {
                $table->unsignedInteger('scan_count')->default(0)->after('checked_in_by');
            }
            if (!Schema::hasColumn('pembelian_tiket','last_scanned_at')) {
                $table->timestamp('last_scanned_at')->nullable()->after('scan_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembelian_tiket', function (Blueprint $table) {
            if (Schema::hasColumn('pembelian_tiket','last_scanned_at')) $table->dropColumn('last_scanned_at');
            if (Schema::hasColumn('pembelian_tiket','scan_count')) $table->dropColumn('scan_count');
            if (Schema::hasColumn('pembelian_tiket','checked_in_by')) $table->dropColumn('checked_in_by');
            if (Schema::hasColumn('pembelian_tiket','checked_in_at')) $table->dropColumn('checked_in_at');
        });
    }
};
