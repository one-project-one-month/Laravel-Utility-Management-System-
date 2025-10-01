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
        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('room_no')->unique();
            $table->string('dimension');
            $table->integer('no_of_bed_room');
            $table->enum('status',['Available','Rented','Purchased','In Maintenance']);
            $table->decimal('selling_price', 20, 2);
            $table->integer('max_no_of_people');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Drop the old enum constraint
        DB::statement('ALTER TABLE rooms DROP CONSTRAINT IF EXISTS rooms_status_check');

        // Add the updated constraint
        DB::statement("
            ALTER TABLE rooms
            ADD CONSTRAINT rooms_status_check
            CHECK (status IN ('Available', 'Rented', 'Purchased', 'In Maintenance'))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');

        // Rollback: remove the constraint
        DB::statement('ALTER TABLE rooms DROP CONSTRAINT IF EXISTS rooms_status_check');

        // (Optional) add back the old version
        DB::statement("
            ALTER TABLE rooms
ADD CONSTRAINT rooms_status_check
CHECK (status IN ('Available', 'Rented', 'Purchased', 'In Maintenance'))
        ");


    }
};
