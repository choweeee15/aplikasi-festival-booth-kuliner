<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('booths')) {
            Schema::create('booths', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('admin_id')->nullable()->index();
                $table->string('nama_booth', 100);
                $table->text('deskripsi')->nullable();
                $table->decimal('harga_sewa', 10, 2);
                $table->enum('status', ['tersedia','disewa'])->default('tersedia');
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('booths');
    }
};
