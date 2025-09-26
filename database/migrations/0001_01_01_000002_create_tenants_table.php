<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            // $table->text('name')->nullable();  //Check
            // $table->text('nrc')->nullable();  //Check
            // $table->text('email'); //Check
            // $table->text('phone_no'); //Check
            // $table->text('emergency_no'); //Check
            $table->timestamps();


            // DB::statement('ALTER TABLE posts ALTER COLUMN tags TYPE text[] USING tags::text[];');
            // DB::statement('ALTER TABLE posts ALTER COLUMN tags TYPE text[] USING tags::text[];');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
