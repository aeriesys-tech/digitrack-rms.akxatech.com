<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_services', function (Blueprint $table) {
            $table->id('user_service_id');
            $table->foreignId('plant_id');
            $table->foreignId('service_id');
            $table->foreignId('user_id');
            $table->string('service_no');
            $table->string('service_cost')->nullable();
            $table->foreignId('asset_id');
            $table->timestamp('service_date');
            $table->timestamp('next_service_date')->nullable();
            $table->Text('note')->nullable();
            $table->timestamps();
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('service_id')->references('service_id')->on('services');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_services');
    }
};
