<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['scooter', 'skateboard', 'bicycle']);
            $table->string('brand');
            $table->string('model');
            $table->string('plate')->unique();
            $table->enum('status', ['available', 'maintenance', 'reserved', 'damaged', 'in_use', 'charging'])->default('available');
            $table->decimal('current_location_lat', 10, 7)->nullable();
            $table->decimal('current_location_lng', 10, 7)->nullable();
            $table->foreignId('station_id')->nullable()->constrained('stations')->onDelete('set null');
            $table->timestamps();

            $table->index('status');
            $table->index('type');
            $table->index(['station_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
