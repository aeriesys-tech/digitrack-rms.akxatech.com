<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variables', function (Blueprint $table) {
            $table->id('variable_id');
            $table->foreignId('variable_type_id');
            $table->string('variable_code',100);
            $table->string('variable_name',100);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('variable_type_id')->references('variable_type_id')->on('variable_types');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variables');
    }
};
