<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $col) {
            $col->id();
            $col->foreignId('business_id')->constrained()->onDelete('cascade');
            $col->string('name');
            $col->string('email')->nullable();
            $col->string('phone', 20)->nullable();
            $col->string('specialty')->nullable();
            $col->jsonb('working_hours')->nullable();
            $col->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
};
