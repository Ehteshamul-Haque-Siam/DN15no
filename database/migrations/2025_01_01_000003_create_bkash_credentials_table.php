<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bkash_credentials', function (Blueprint $table) {
            $table->id();
            $table->text('app_key');
            $table->text('app_secret');
            $table->text('username');
            $table->text('password');
            $table->string('merchant_number')->nullable();
            $table->enum('environment', ['sandbox', 'live'])->default('sandbox');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bkash_credentials');
    }
};