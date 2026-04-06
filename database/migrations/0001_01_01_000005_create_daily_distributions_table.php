<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->date('distribution_date');
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            // Unique constraint: one distribution per company per day
            $table->unique(['distribution_date', 'company_id', 'deleted_at'], 'dist_date_co_deleted');
        });

        // Pivot table for distribution_worker relationship
        Schema::create('distribution_worker', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribution_id')->constrained('daily_distributions')->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained('workers')->cascadeOnDelete();
            $table->timestamps();
            
            // Prevent duplicate assignments
            $table->unique(['distribution_id', 'worker_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribution_worker');
        Schema::dropIfExists('daily_distributions');
    }
};
