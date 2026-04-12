<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if is_blocked column exists
        if (Schema::hasColumn('users', 'is_blocked')) {
            Schema::table('users', function (Blueprint $table) {
                // Create the new status column
                $table->enum('status', ['active', 'inactive', 'other'])->default('active')->after('is_blocked');
            });

            // Migrate data from is_blocked to status
            DB::update('UPDATE users SET status = CASE WHEN is_blocked = 1 THEN "inactive" ELSE "active" END');

            Schema::table('users', function (Blueprint $table) {
                // Drop the old column
                $table->dropColumn('is_blocked');
            });
        } else {
            // If is_blocked doesn't exist, just add status
            Schema::table('users', function (Blueprint $table) {
                $table->enum('status', ['active', 'inactive', 'other'])->default('active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false)->after('city');
        });

        // Migrate data back
        DB::update('UPDATE users SET is_blocked = CASE WHEN status = "inactive" THEN 1 ELSE 0 END');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
