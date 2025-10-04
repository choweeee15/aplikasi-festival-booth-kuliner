<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_activities', function (Blueprint $table) {
            $table->id(); // id auto-increment bigint
            $table->string('username', 100);
            $table->text('activity');
            $table->string('ip_address', 50);
            $table->timestamp('created_at')->useCurrent(); // hanya created_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_activities');
    }
};