<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_id')->constrained()->restrictOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            // transaction_id: plain column (no FK constraint — transactions table created later to avoid circular ref)
            $table->unsignedBigInteger('transaction_id')->nullable()->index();

            // Slug & URL
            $table->string('slug')->unique();
            $table->string('custom_domain')->nullable()->unique();

            // Couple info
            $table->string('groom_name');           // Short name (display)
            $table->string('bride_name');
            $table->string('groom_full_name')->nullable();
            $table->string('bride_full_name')->nullable();
            $table->string('groom_father')->nullable();
            $table->string('groom_mother')->nullable();
            $table->string('bride_father')->nullable();
            $table->string('bride_mother')->nullable();
            $table->string('groom_photo')->nullable();
            $table->string('bride_photo')->nullable();

            // Cover
            $table->string('cover_photo')->nullable();
            $table->string('opening_quote')->nullable();
            $table->string('opening_quote_source')->nullable();

            // Wedding story
            $table->text('story')->nullable();

            // Music
            $table->string('music_url')->nullable();
            $table->string('music_name')->nullable();
            $table->boolean('music_autoplay')->default(false);

            // Status & visibility
            $table->boolean('is_published')->default(false);
            $table->boolean('is_active')->default(false);  // activated after payment
            $table->boolean('is_open')->default(true);     // can accept new RSVPs

            // Expiration
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // RSVP deadline
            $table->date('rsvp_deadline')->nullable();

            // Counting
            $table->unsignedInteger('view_count')->default(0);

            // Meta (SEO, custom theme overrides)
            $table->json('meta')->nullable();

            // Theme overrides stored as JSON
            $table->json('theme')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index(['slug', 'is_active']);
            $table->index('custom_domain');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
