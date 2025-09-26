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
            // $table->foreignId('room_id')->references('id')->on('room')->onDelete('cascade');
            $table->uuid('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->integer('rental_fee');
            $table->integer('electricity_fee');
            $table->integer('water_fee');
            $table->integer('fine_fee')->nullable();
            $table->integer('service_fee');
            $table->integer('ground_fee');
            $table->integer('car_parking_fee')->nullable();
            $table->integer('wifi_fee')->nullable();
            $table->integer('total_amount');
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
