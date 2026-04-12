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
        Schema::create('overtime_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('workers')->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->foreignId('contractor_id')->constrained('users')->onDelete('cascade');
            
            // Distribution data
            $table->date('week_start');
            $table->date('week_end');
            $table->decimal('total_overtime_hours', 4, 1)->default(0);
            $table->decimal('total_overtime_amount', 10, 2)->default(0);
            
            // Individual day records
            $table->json('daily_records'); // Array of {date, hours, rate, amount}
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indices
            $table->index(['worker_id', 'week_start']);
            $table->index(['payment_id']);
            $table->index(['contractor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('overtime_archives');
    }
};
