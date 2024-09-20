<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id('user_activity_id');
            $table->string('activity_no');
            $table->timestamp('activity_date');
            $table->foreignId('user_id');
            $table->foreignId('asset_id');
            $table->string('status');
            $table->foreignId('reason_id');
            $table->foreignId('equipment_id')->nullable();
            $table->string('cost')->nullable();
            $table->Text('note')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('reason_id')->references('reason_id')->on('reasons');
            $table->foreign('equipment_id')->references('equipment_id')->on('equipment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
