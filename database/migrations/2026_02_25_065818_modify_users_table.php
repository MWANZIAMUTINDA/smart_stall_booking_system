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
    Schema::table('users', function (Blueprint $table) {
        $table->string('username')->unique()->nullable();
        $table->string('phone_number')->unique();
        $table->enum('role', ['trader','admin','officer'])->default('trader');
        $table->enum('status', ['active','suspended','deleted'])->default('active');
        $table->timestamp('last_login')->nullable();
        $table->string('ussd_pin_hash')->nullable();
    });
}
};
