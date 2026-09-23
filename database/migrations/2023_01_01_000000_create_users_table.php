<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $col) {
            $col->id();
            $col->string('name');
            $col->string('email')->unique();
            $col->string('password');
            $col->string('role', 20); // business, customer
            $col->string('phone', 20)->nullable();
            $col->timestamp('email_verified_at')->nullable();
            $col->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
