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
            $table->timestamp('delivered_at')->nullable()->after('total_price');
            $table->foreignId('delivered_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('delivery_notes')->nullable();
            $table->enum('delivery_condition', ['good', 'fair', 'poor'])->nullable();
            
            $table->timestamp('returned_at')->nullable();
            $table->foreignId('returned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('return_notes')->nullable();
            $table->enum('return_condition', ['good', 'fair', 'poor'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['delivered_by']);
            $table->dropForeign(['returned_by']);
            $table->dropColumn([
                'delivered_at',
                'delivered_by',
                'delivery_notes',
                'delivery_condition',
                'returned_at',
                'returned_by',
                'return_notes',
                'return_condition',
            ]);
        });
    }
};
