<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained('workers')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->date('advance_date');
            $table->text('notes')->nullable();
            $table->boolean('is_settled')->default(false);
            $table->date('settled_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advances');
    }
};
