<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('tiket')) {
            Schema::create('tiket', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('admin_id')->nullable()->index();
                $table->string('nama_tiket', 100);
                $table->decimal('harga', 10, 2);
                $table->integer('stok')->default(0);
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tiket');
    }
};
