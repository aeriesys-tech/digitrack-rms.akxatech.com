<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_asset_variables', function (Blueprint $table) {
            $table->id('user_asset_variable_id');
            $table->foreignId('user_variable_id');
            $table->foreignId('variable_id');
            $table->foreignId('asset_variable_id')->nullable();
            $table->timestamp('date_time');
            $table->string('value', 255)->nullable();
            $table->timestamps();
            $table->foreign('user_variable_id')->references('user_variable_id')->on('user_variables');
            $table->foreign('variable_id')->references('variable_id')->on('variables');
            $table->foreign('asset_variable_id')->references('asset_variable_id')->on('asset_variables');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_asset_variables');
    }
};
