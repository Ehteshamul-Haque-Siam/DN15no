<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_id')->unique();
            $table->string('name_en');
            $table->string('name_bn');
            $table->string('nickname');
            $table->string('father_en');
            $table->string('father_bn');
            $table->string('mother_en');
            $table->string('mother_bn');
            $table->string('photo')->nullable();
            $table->string('flat');
            $table->string('from_year');
            $table->string('to_year');
            $table->text('present_add')->nullable();
            $table->string('contact_no');
            $table->string('occupation')->nullable();
            $table->string('email')->nullable();
            $table->decimal('membership_fee', 10, 2)->default(1020.00);

            $table->string('mfs_no')->nullable();
            $table->string('mfs_trn')->nullable();
            $table->string('payment_ref')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'verified', 'rejected'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('admin_remarks')->nullable();

            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();

            $table->string('tracking_token', 64)->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};