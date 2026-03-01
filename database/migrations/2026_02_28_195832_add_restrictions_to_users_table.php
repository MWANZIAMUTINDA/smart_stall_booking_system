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
    Schema::table('users', function (Blueprint $table) {
        // Options: none, warned, blocked, banned
        $table->string('account_restriction')->default('none')->after('status'); 
        $table->text('restriction_reason')->nullable()->after('account_restriction');
    });
}

};
