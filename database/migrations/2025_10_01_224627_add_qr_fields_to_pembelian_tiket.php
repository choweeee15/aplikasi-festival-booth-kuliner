<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelian_tiket', function (Blueprint $table) {
            if (!Schema::hasColumn('pembelian_tiket', 'kode')) {
                $table->string('kode')->unique()->after('id');
            }
            if (!Schema::hasColumn('pembelian_tiket', 'qr_token')) {
                $table->string('qr_token', 64)->unique()->after('kode');
            }
            if (!Schema::hasColumn('pembelian_tiket', 'status')) {
                $table->enum('status', ['unpaid', 'paid', 'cancelled'])->default('paid')->after('total');
            }
            if (!Schema::hasColumn('pembelian_tiket', 'checked_in_at')) {
                $table->timestamp('checked_in_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembelian_tiket', function (Blueprint $table) {
            if (Schema::hasColumn('pembelian_tiket', 'checked_in_at')) $table->dropColumn('checked_in_at');
            if (Schema::hasColumn('pembelian_tiket', 'status')) $table->dropColumn('status');
            if (Schema::hasColumn('pembelian_tiket', 'qr_token')) $table->dropColumn('qr_token');
            if (Schema::hasColumn('pembelian_tiket', 'kode')) $table->dropColumn('kode');
        });
    }
};
