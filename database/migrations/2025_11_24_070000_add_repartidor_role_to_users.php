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
        // Agregar 'repartidor' al ENUM de role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'cliente', 'operador', 'tecnico', 'repartidor') NOT NULL");
        
        // Agregar campos para repartidores
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_available')->default(false)->after('role');
            $table->time('shift_start')->nullable()->after('is_available');
            $table->time('shift_end')->nullable()->after('shift_start');
            $table->string('vehicle_type')->nullable()->after('shift_end'); // carro, moto
            $table->string('license_number')->nullable()->after('vehicle_type');
            $table->integer('total_deliveries')->default(0)->after('license_number');
            $table->decimal('average_rating', 3, 2)->default(0)->after('total_deliveries');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_available',
                'shift_start',
                'shift_end',
                'vehicle_type',
                'license_number',
                'total_deliveries',
                'average_rating'
            ]);
        });
        
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'cliente', 'operador', 'tecnico') NOT NULL");
    }
};
