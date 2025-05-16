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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['internal', 'external'])->default('internal')->after('email');
            $table->string('phone_number', 20)->nullable()->after('user_type');
            $table->string('position')->nullable()->after('phone_number');
            $table->string('department')->nullable()->after('position');
            $table->text('address')->nullable()->after('department');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'phone_number', 'position', 'department', 'address']);
        });
    }
};
