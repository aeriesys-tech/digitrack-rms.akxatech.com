<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_types', function (Blueprint $table) {
            $table->id('spare_type_id');
            $table->string('spare_type_code',100);
            $table->string('spare_type_name',100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_types');
    }
};
