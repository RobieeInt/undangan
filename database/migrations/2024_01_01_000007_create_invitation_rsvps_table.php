<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitation_rsvps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->nullable()->constrained('invitation_guests')->nullOnDelete();
            $table->string('name');
            $table->string('phone', 20)->nullable();
            $table->enum('attendance', ['hadir', 'tidak_hadir', 'mungkin'])->default('hadir');
            $table->unsignedSmallInteger('guest_count')->default(1);
            $table->text('message')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('invitation_id');
            $table->index('guest_id');
            $table->index('attendance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_rsvps');
    }
};
