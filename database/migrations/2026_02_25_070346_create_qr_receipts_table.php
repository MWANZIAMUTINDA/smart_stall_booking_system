<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('qr_receipts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('booking_id')->constrained()->onDelete('cascade');
        $table->text('qr_code_data');
        $table->string('qr_image_url')->nullable();
        $table->enum('status',['active','used','revoked','expired'])->default('active');
        $table->timestamp('validated_at')->nullable();
        $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
        $table->integer('scan_count')->default(0);
        $table->timestamps();
    });
}
};
