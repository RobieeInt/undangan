<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invitation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('package_id')->constrained()->restrictOnDelete();

            $table->string('order_id')->unique();   // our internal order ID
            $table->string('midtrans_transaction_id')->nullable();
            $table->unsignedBigInteger('gross_amount');  // in IDR
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'refund'])->default('pending');
            $table->string('payment_type')->nullable();  // credit_card, gopay, etc
            $table->string('payment_method')->nullable();
            $table->string('snap_token')->nullable();
            $table->text('snap_redirect_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->json('midtrans_response')->nullable(); // raw webhook payload
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('order_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
