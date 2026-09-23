<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('businesses', function (Blueprint $col) {
            $col->id();
            $col->foreignId('user_id')->constrained()->onDelete('cascade');
            $col->string('name');
            $col->text('description')->nullable();
            $col->text('address')->nullable();
            $col->string('phone', 20)->nullable();
            $col->string('email')->nullable();
            $col->string('logo_url')->nullable();
            $col->string('website')->nullable();
            $col->string('timezone', 50)->default('UTC');
            $col->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('businesses');
    }
};
