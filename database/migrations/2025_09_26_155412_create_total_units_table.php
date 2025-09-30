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
        Schema::create('total_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->decimal('electricity_units',20,2);
            $table->decimal('water_units',20,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_units');
    }
};
