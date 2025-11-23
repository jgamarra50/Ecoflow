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
            // Add indexes for frequently queried columns
            $table->index('status');
            $table->index(['start_date', 'end_date']);
            $table->index('created_at');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->index('status');
            $table->index('type');
            $table->index(['station_id', 'status']);
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->index('status');
            $table->index('priority');
            $table->index('technician_id');
            $table->index(['status', 'priority']);
            $table->index('created_at');
        });

        Schema::table('telemetries', function (Blueprint $table) {
            // Index for getting latest telemetry per vehicle
            $table->index(['vehicle_id', 'created_at']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['start_date', 'end_date']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['type']);
            $table->dropIndex(['station_id', 'status']);
        });

        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['priority']);
            $table->dropIndex(['technician_id']);
            $table->dropIndex(['status', 'priority']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('telemetries', function (Blueprint $table) {
            $table->dropIndex(['vehicle_id', 'created_at']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
};
