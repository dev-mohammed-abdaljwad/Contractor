<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daily_distributions', function (Blueprint $table) {
            // Snapshot of what workers receive per day in this distribution.
            // Captured at creation time so historical profit stays accurate even if rates change later.
            $table->decimal('worker_daily_wage', 10, 2)->default(0)->after('overtime_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_distributions', function (Blueprint $table) {
            $table->dropColumn('worker_daily_wage');
        });
    }
};
