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
        Schema::table('reservations', function (Blueprint $table) {
            // Delivery tracking
            $table->boolean('documents_verified')->default(false)->after('delivery_condition');
            $table->string('delivery_photo_url')->nullable()->after('documents_verified');
            
            // Return tracking
            $table->integer('return_kilometers')->nullable()->after('return_condition');
            $table->integer('return_battery_level')->nullable()->after('return_kilometers');
            $table->string('return_photo_url')->nullable()->after('return_battery_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'documents_verified',
                'delivery_photo_url',
                'return_kilometers',
                'return_battery_level',
                'return_photo_url',
            ]);
        });
    }
};
