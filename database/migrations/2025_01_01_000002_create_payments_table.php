<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Add new columns
            $table->string('building_no', 50)->nullable()->after('photo');
            $table->string('flat_no', 50)->nullable()->after('building_no');
        });

        // Migrate old `flat` values into `building_no` (best-effort)
        if (Schema::hasColumn('registrations', 'flat')) {
            \DB::table('registrations')
                ->whereNull('building_no')
                ->update(['building_no' => \DB::raw('`flat`')]);
        }

        // Drop the old column
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'flat')) {
                $table->dropColumn('flat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('flat')->nullable()->after('photo');
        });

        \DB::table('registrations')->update([
            'flat' => \DB::raw('`building_no`'),
        ]);

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['building_no', 'flat_no']);
        });
    }
};