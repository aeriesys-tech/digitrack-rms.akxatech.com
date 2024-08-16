<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_abilities', function (Blueprint $table) {
            $table->id('role_ability_id');
            $table->foreignId('role_id');
            $table->foreignId('ability_id');
            $table->timestamps();
            $table->foreign('role_id')->references('role_id')->on('roles');
            $table->foreign('ability_id')->references('ability_id')->on('abilities');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_abilities');
    }
};
