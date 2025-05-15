<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE bookings MODIFY payment_status ENUM('unpaid', 'pending', 'paid', 'cancelled') DEFAULT 'unpaid'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE bookings MODIFY payment_status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending'");
    }
};
