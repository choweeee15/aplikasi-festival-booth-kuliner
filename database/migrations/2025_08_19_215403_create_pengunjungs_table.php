<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('pengunjungs')) {
            Schema::create('pengunjungs', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama', 100);
                $table->string('email', 100)->unique();
                $table->string('password', 255);
                $table->string('no_hp', 20)->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengunjungs');
    }
};
