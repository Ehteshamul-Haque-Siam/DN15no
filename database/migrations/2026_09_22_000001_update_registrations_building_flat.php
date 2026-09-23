<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('registrations', 'building_no')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('building_no', 50)->nullable()->after('photo');
            });
        }

        if (!Schema::hasColumn('registrations', 'flat_no')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('flat_no', 50)->nullable()->after('building_no');
            });
        }

        if (Schema::hasColumn('registrations', 'flat')) {
            \DB::table('registrations')
                ->whereNull('building_no')
                ->update(['building_no' => \DB::raw('`flat`')]);

            Schema::table('registrations', function (Blueprint $table) {
                $table->dropColumn('flat');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('registrations', 'flat')) {
            Schema::table('registrations', function (Blueprint $table) {
                $table->string('flat')->nullable()->after('photo');
            });
        }

        if (Schema::hasColumn('registrations', 'building_no')) {
            \DB::table('registrations')->update([
                'flat' => \DB::raw('`building_no`'),
            ]);
        }

        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'building_no')) {
                $table->dropColumn('building_no');
            }
            if (Schema::hasColumn('registrations', 'flat_no')) {
                $table->dropColumn('flat_no');
            }
        });
    }
};