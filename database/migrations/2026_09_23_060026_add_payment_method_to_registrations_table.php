<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'payment_method')) {
                $table->enum('payment_method', ['bkash', 'bank', 'cash'])
                    ->default('bkash')
                    ->after('membership_fee');
            }
            if (!Schema::hasColumn('registrations', 'bank_id')) {
                $table->unsignedBigInteger('bank_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('registrations', 'bank_reference')) {
                $table->string('bank_reference')->nullable()->after('bank_id');
            }
            if (!Schema::hasColumn('registrations', 'bank_slip')) {
                $table->string('bank_slip')->nullable()->after('bank_reference');
            }
            if (!Schema::hasColumn('registrations', 'paid_to_bank')) {
                $table->string('paid_to_bank')->nullable()->after('bank_slip');
            }
            if (!Schema::hasColumn('registrations', 'cash_receipt_no')) {
                $table->string('cash_receipt_no')->nullable()->after('paid_to_bank');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method', 'bank_id', 'bank_reference',
                'bank_slip', 'paid_to_bank', 'cash_receipt_no',
            ]);
        });
    }
};