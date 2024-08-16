<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_parameter_types', function (Blueprint $table) {
            $table->id('asset_parameter_type_id');
            $table->foreignId('asset_parameter_id');
            $table->foreign('asset_parameter_id')->references('asset_parameter_id')->on('asset_parameters');
            $table->foreignId('asset_type_id');
            $table->foreign('asset_type_id')->references('asset_type_id')->on('asset_type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_parameter_types');
    }
};
