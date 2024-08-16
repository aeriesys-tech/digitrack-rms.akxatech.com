<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_spares', function (Blueprint $table) {
            $table->id('user_spare_id');
            $table->foreignId('user_service_id');
            $table->foreignId('spare_id');
            $table->string('spare_cost')->nullable();
            $table->timestamps();
            $table->foreign('user_service_id')->references('user_service_id')->on('user_services');
            $table->foreign('spare_id')->references('spare_id')->on('spares');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_spares');
    }
};
