<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guest_checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->constrained('invitation_guests')->cascadeOnDelete();
            $table->foreignId('rsvp_id')->nullable()->constrained('invitation_rsvps')->nullOnDelete();
            $table->timestamp('checked_in_at');
            $table->string('checked_in_by')->nullable(); // who scanned (admin name/device)
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->unique('guest_id'); // one check-in per guest
            $table->index('invitation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_checkins');
    }
};
