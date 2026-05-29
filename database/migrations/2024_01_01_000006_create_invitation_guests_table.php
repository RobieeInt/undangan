<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitation_guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();   // for personalized URL
            $table->string('phone', 20)->nullable();
            $table->string('qr_code')->nullable(); // path to QR image
            $table->string('qr_token', 64)->unique()->nullable(); // secret token for QR
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('allocated_seats')->default(1);
            $table->timestamps();

            $table->index('invitation_id');
            $table->index('qr_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitation_guests');
    }
};
