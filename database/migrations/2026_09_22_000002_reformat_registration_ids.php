<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Prepend "D15-" to any registration_id that doesn't already start with it
        $rows = DB::table('registrations')
            ->where('registration_id', 'NOT LIKE', 'D15-%')
            ->orderBy('id')
            ->get();

        foreach ($rows as $row) {
            $newId = 'D15-' . $row->registration_id;

            // Skip if the new ID already exists (paranoia)
            if (DB::table('registrations')->where('registration_id', $newId)->exists()) {
                continue;
            }

            DB::table('registrations')
                ->where('id', $row->id)
                ->update(['registration_id' => $newId]);
        }
    }

    public function down(): void
    {
        // Remove the D15- prefix
        $rows = DB::table('registrations')
            ->where('registration_id', 'LIKE', 'D15-%')
            ->orderBy('id')
            ->get();

        foreach ($rows as $row) {
            DB::table('registrations')
                ->where('id', $row->id)
                ->update([
                    'registration_id' => preg_replace('/^D15-/', '', $row->registration_id),
                ]);
        }
    }
};