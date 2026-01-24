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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('order_detail_id');
            $table->integer('class_id');
            $table->integer('trainer_id');
            $table->integer('schedule_id');
            $table->dateTime('booking_datetime');
            $table->string('status');
            $table->boolean('is_active')->default(true);
            $table->timestamps;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
