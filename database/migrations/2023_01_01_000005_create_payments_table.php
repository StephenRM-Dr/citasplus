<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $col) {
            $col->id();
            $col->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $col->decimal('amount', 10, 2);
            $col->string('status', 20)->default('pending'); // pending, completed, failed, refunded
            $col->string('payment_method', 50)->nullable();
            $col->string('transaction_id')->nullable();
            $col->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
