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
    Schema::create('order_details', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('order_id'); // wajib keisi
        $table->string('package_id');
        $table->string('class_id');
        $table->string('package_name');
        $table->string('class_name');
        $table->integer('total_quota');
        $table->integer('used_quota')->default(0);
        $table->integer('remaining_quota');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('order_details');
}

};
