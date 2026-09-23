<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $col) {
            $col->id();
            $col->foreignId('business_id')->constrained()->onDelete('cascade');
            $col->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $col->foreignId('service_id')->constrained()->onDelete('cascade');
            $col->foreignId('employee_id')->constrained()->onDelete('cascade');
            $col->timestamp('start_time');
            $col->timestamp('end_time');
            $col->string('status', 20)->default('confirmed'); // pending, confirmed, completed, cancelled, no_show
            $col->text('notes')->nullable();
            $col->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
