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
        Schema::create('contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('contract_type_id')->nullable()->references('id')->on('contract_types')->onDelete('cascade');
            $table->uuid('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreignId('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->date('expiry_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
