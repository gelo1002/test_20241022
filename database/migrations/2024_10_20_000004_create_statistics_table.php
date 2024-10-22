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
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained('visitors')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->string('jyv', 50)->nullable();
            $table->string('badmail', 50)->nullable();
            $table->string('disable', 50)->nullable();
            $table->dateTime('date_shipment')->nullable();
            $table->dateTime('date_open')->nullable();
            $table->integer('opens')->nullable();
            $table->integer('opens_viral')->nullable();
            $table->dateTime('date_click')->nullable();
            $table->integer('clicks')->nullable();
            $table->integer('clicks_viral')->nullable();
            $table->string('links',500)->nullable();
            $table->string('ips',500)->nullable();
            $table->string('browsers',500)->nullable();
            $table->string('platforms',500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistics');
    }
};
