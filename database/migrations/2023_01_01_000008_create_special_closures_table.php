<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('special_closures', function (Blueprint $col) {
            $col->id();
            $col->foreignId('business_id')->constrained()->onDelete('cascade');
            $col->date('closure_date');
            $col->string('reason')->nullable();
            $col->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('special_closures');
    }
};
