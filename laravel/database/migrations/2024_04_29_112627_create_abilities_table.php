<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abilities', function (Blueprint $table) {
            $table->id('ability_id');
            $table->string('ability');
            $table->string('description')->nullable();
            $table->foreignId('module_id');
            $table->timestamps();
            $table->foreign('module_id')->references('module_id')->on('modules');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abilities');
    }
};
