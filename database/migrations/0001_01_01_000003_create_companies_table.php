<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('contact_person');
            $table->string('phone', 20);
            $table->decimal('daily_wage', 10, 2);
            $table->enum('payment_cycle', ['daily', 'weekly', 'bimonthly']);
            $table->string('weekly_pay_day')->nullable();
            $table->date('contract_start_date');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
