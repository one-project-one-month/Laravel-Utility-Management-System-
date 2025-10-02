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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->uuid('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->uuid('user_id');
            $table->decimal('rental_fee',20,2);
            $table->decimal('electricity_fee',20,2);
            $table->decimal('water_fee',20,2);
            $table->decimal('fine_fee',20,2)->nullable();
            $table->decimal('service_fee',20,2);
            $table->decimal('ground_fee',20,2);
            $table->decimal('car_parking_fee',20,2)->nullable();
            $table->decimal('wifi_fee',20,2)->nullable();
            $table->decimal('total_amount',20,2);
            $table->date('due_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
