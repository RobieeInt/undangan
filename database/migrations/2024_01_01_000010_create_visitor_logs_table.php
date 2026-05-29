<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->enum('device_type', ['mobile', 'tablet', 'desktop'])->default('mobile');
            $table->string('referrer')->nullable();
            $table->string('guest_slug')->nullable(); // which guest link was used
            $table->timestamp('visited_at');

            $table->index(['invitation_id', 'visited_at']);
            $table->index('device_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
