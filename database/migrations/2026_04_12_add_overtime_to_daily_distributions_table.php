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
            $table->decimal('overtime_hours', 4, 1)->default(0)->after('total_amount');
            $table->decimal('overtime_rate', 8, 2)->default(0)->after('overtime_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_distributions', function (Blueprint $table) {
            $table->dropColumn(['overtime_hours', 'overtime_rate']);
        });
    }
};
