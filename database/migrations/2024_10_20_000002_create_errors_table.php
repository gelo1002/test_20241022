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
        Schema::create('type_errors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->nullable()->constrained('files')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreignId('type_error_id')->constrained('type_errors')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->string('detail', 1000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errors');
        Schema::dropIfExists('type_errors');
    }
};
