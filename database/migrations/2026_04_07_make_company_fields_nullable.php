<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('contact_person')->nullable()->change();
            $table->string('phone', 20)->nullable()->change();
            $table->text('notes')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('contact_person')->nullable(false)->change();
            $table->string('phone', 20)->nullable(false)->change();
            $table->text('notes')->nullable(false)->change();
        });
    }
};
