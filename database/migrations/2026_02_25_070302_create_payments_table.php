<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('booking_id')->constrained()->onDelete('cascade');
        $table->string('mpesa_transaction_id')->unique();
        $table->string('phone_number');
        $table->decimal('amount',10,2);
        $table->enum('payment_status',['pending','success','failed','reversed','cancelled'])->default('pending');
        $table->enum('payment_method',['mpesa_stk','mpesa_paybill','ussd','manual']);
        $table->string('daraja_checkout_id')->nullable();
        $table->json('daraja_callback_data')->nullable();
        $table->dateTime('payment_time')->nullable();
        $table->dateTime('confirmed_at')->nullable();
        $table->timestamps();
    });
}
};
