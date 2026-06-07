<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->text('gift_address')->nullable()->after('story');
        });
        Schema::table('invitation_rsvps', function (Blueprint $table) {
            $table->string('gift_name', 200)->nullable()->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn('gift_address');
        });
        Schema::table('invitation_rsvps', function (Blueprint $table) {
            $table->dropColumn('gift_name');
        });
    }
};
