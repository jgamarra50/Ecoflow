<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar el ENUM para incluir 'motorcycle electric'
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN type ENUM('scooter', 'skateboard', 'bicycle', 'motorcycle electric') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al ENUM original sin 'motorcycle electric'
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN type ENUM('scooter', 'skateboard', 'bicycle') NOT NULL");
    }
};
