<?php

use Illuminate\Support\Facades\DB;
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
            $table->timestamps();
        });
        // Text Array
        DB::statement('ALTER TABLE tenants ADD COLUMN names text[]');
        DB::statement('ALTER TABLE tenants ADD COLUMN nrcs text[]');
        DB::statement('ALTER TABLE tenants ADD COLUMN emails text[]');
        DB::statement('ALTER TABLE tenants ADD COLUMN phone_nos text[]');
        DB::statement('ALTER TABLE tenants ADD COLUMN emergency_nos text[]');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
