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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('delivery_method', ['pickup', 'delivery']);
            $table->text('delivery_address')->nullable();
            $table->foreignId('station_id')->nullable()->constrained('stations')->onDelete('set null');
            $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled'])->default('pending');
            $table->decimal('total_price', 10, 2);

            $table->timestamp('delivered_at')->nullable();
            $table->foreignId('delivered_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('delivery_notes')->nullable();
            $table->enum('delivery_condition', ['good', 'fair', 'poor'])->nullable();

            $table->timestamp('returned_at')->nullable();
            $table->foreignId('returned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('return_notes')->nullable();
            $table->enum('return_condition', ['good', 'fair', 'poor'])->nullable();

            $table->boolean('documents_verified')->default(false);
            $table->string('delivery_photo_url')->nullable();

            $table->integer('return_kilometers')->nullable();
            $table->integer('return_battery_level')->nullable();
            $table->string('return_photo_url')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index(['start_date', 'end_date']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
