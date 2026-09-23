<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reminders', function (Blueprint $col) {
            $col->id();
            $col->foreignId('business_id')->constrained()->onDelete('cascade');
            $col->string('type', 20); // email, sms, whatsapp
            $col->integer('send_before'); // horas antes
            $col->text('message_template')->nullable();
            $col->boolean('active')->default(true);
            $col->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reminders');
    }
};
