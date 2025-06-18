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
        Schema::create('room_equipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('building_id');
            $table->unsignedBigInteger('room_id');
            $table->string('name');
            $table->integer('quantity')->default(1);
            $table->text('note')->nullable();
            $table->timestamps();

            // สร้าง foreign key แบบ composite
            $table->foreign(['building_id', 'room_id'])
                ->references(['building_id', 'room_id'])
                ->on('rooms')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_equipments');
    }
};
