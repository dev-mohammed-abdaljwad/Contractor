<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained('workers')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->date('deduction_date');
            $table->enum('type', ['quarter', 'half', 'full', 'custom']);
            $table->decimal('amount', 10, 2);
            $table->text('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deductions');
    }
};
