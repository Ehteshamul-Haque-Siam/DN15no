<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->string('gateway')->default('bkash');
            $table->string('payment_id')->nullable();
            $table->string('trx_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 5)->default('BDT');
            $table->string('payer_mobile')->nullable();
            $table->enum('status', ['initiated', 'success', 'failed', 'refunded', 'pending_verification'])->default('initiated');
            $table->json('gateway_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};