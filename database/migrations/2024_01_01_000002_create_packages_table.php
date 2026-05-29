<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price');          // in IDR (cents not used)
            $table->unsignedSmallInteger('duration_days'); // validity period
            $table->unsignedSmallInteger('max_guests')->default(100);
            $table->unsignedSmallInteger('max_gallery')->default(10);
            $table->boolean('has_watermark')->default(true);
            $table->boolean('has_analytics')->default(false);
            $table->boolean('has_rsvp_export')->default(false);
            $table->boolean('has_custom_domain')->default(false);
            $table->boolean('has_all_templates')->default(false);
            $table->boolean('has_qr_checkin')->default(false);
            $table->unsignedSmallInteger('max_music')->default(1);
            $table->json('features')->nullable();          // extra feature list for display
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
