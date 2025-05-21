<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('booking_history', function (Blueprint $table) {
            $table->date('booking_date')->nullable()->after('booking_end');
        });
    }

    public function down()
    {
        Schema::table('booking_history', function (Blueprint $table) {
            $table->dropColumn('booking_date');
        });
    }
};
