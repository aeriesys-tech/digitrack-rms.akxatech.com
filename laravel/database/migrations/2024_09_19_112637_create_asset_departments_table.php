<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_departments', function (Blueprint $table) {
            $table->id('asset_department_id');
            $table->foreignId('asset_id');
            $table->foreignId('department_id');
            $table->timestamps();
            $table->foreign('asset_id')->references('asset_id')->on('assets');
            $table->foreign('department_id')->references('department_id')->on('departments');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_departments');
    }
};
