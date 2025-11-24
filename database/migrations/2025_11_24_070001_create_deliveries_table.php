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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('delivery_person_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['delivery', 'pickup']); // entrega o recogida
            $table->enum('status', ['pending', 'assigned', 'in_transit', 'arrived', 'delivered', 'cancelled'])->default('pending');
            $table->datetime('scheduled_time');
            $table->datetime('actual_delivery_time')->nullable();
            $table->string('delivery_address');
            $table->decimal('delivery_lat', 10, 7)->nullable();
            $table->decimal('delivery_lng', 10, 7)->nullable();
            $table->text('notes')->nullable();
            $table->string('photo_proof')->nullable(); // ruta de la foto
            $table->text('customer_signature')->nullable(); // base64 de la firma
            $table->decimal('distance_km', 8, 2)->nullable();
            $table->decimal('delivery_fee', 10, 2)->default(10000); // Costo de entrega
            $table->timestamps();
            
            $table->index('status');
            $table->index('delivery_person_id');
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
