<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $col) {
            $col->id();
            $col->foreignId('business_id')->constrained()->onDelete('cascade');
            $col->string('name');
            $col->text('description')->nullable();
            $col->integer('duration'); // en minutos
            $col->decimal('price', 10, 2);
            $col->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
};
