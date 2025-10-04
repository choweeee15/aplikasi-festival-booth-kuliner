<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama', 100);
                $table->string('email', 100)->unique();
                $table->string('password', 255);
                $table->timestamps(); // created_at & updated_at
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
