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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('email', 250)->unique();
            $table->date('date_first_visit');
            $table->date('date_last_visit');
            $table->integer('total_visits');
            $table->integer('visits_current_year');
            $table->integer('visits_current_month');
            $table->string('current_month', 4);
            $table->string('current_year', 4);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
