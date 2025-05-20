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
        Schema::table('booking_history', function (Blueprint $table) {
            $table->timestamp('moved_to_history_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('booking_history', function (Blueprint $table) {
            $table->dropColumn(['booking_date', 'moved_to_history_at']);
        });
    }
};
