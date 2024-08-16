<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spares', function (Blueprint $table) {
            $table->id('spare_id');
            $table->foreignId('spare_type_id');
            $table->string('spare_code',100);
            $table->string('spare_name',100);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('spare_type_id')->references('spare_type_id')->on('spare_types');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spares');
    }
};
