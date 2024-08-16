<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_parameter_values', function (Blueprint $table) {
            $table->id('asset_parameter_value_id');
            $table->foreignId('asset_id');
            $table->foreignId('asset_parameter_id');
            $table->string('field_value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('asset_parameter_id')->references('asset_parameter_id')->on('asset_parameters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_parameter_values');
    }
};
