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
    Schema::create('stalls', function (Blueprint $table) {
        $table->id();
        $table->string('stall_number')->unique();
        $table->string('zone')->nullable();
        $table->string('location_desc')->nullable();
        $table->decimal('latitude', 9,6)->nullable();
        $table->decimal('longitude', 9,6)->nullable();
        $table->enum('status', ['available','booked','maintenance','inactive'])->default('available');
        $table->timestamps();
    });
}
};
