<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('business_hours', function (Blueprint $col) {
            $col->id();
            $col->foreignId('business_id')->constrained()->onDelete('cascade');
            $col->integer('day_of_week'); // 0-6
            $col->time('open_time')->nullable();
            $col->time('close_time')->nullable();
            $col->boolean('is_closed')->default(false);
            $col->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_hours');
    }
};
