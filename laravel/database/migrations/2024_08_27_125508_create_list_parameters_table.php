<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('list_parameters', function (Blueprint $table) {
            $table->id('list_parameter_id');
            $table->string('list_parameter_name');
            $table->text('field_values');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('list_parameters');
    }
};
