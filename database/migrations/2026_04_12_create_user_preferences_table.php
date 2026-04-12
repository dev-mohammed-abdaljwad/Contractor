<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Notifications
            $table->boolean('notify_overdue_payments')->default(true);
            $table->boolean('notify_daily_distribution')->default(true);
            $table->boolean('notify_weekly_report')->default(false);
            $table->boolean('notify_pending_advances')->default(true);
            
            // System settings
            $table->enum('language', ['ar', 'en'])->default('ar');
            $table->enum('currency', ['EGP', 'USD', 'SAR'])->default('EGP');
            $table->decimal('overtime_hourly_rate', 8, 2)->default(20);
            $table->enum('date_format', ['DD/MM/YYYY', 'MM/DD/YYYY', 'YYYY-MM-DD'])->default('DD/MM/YYYY');
            $table->enum('week_start', ['Sunday', 'Monday', 'Saturday'])->default('Sunday');
            $table->boolean('dark_mode')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
