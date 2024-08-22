<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('break_down_types', function (Blueprint $table) {
            $table->id('break_down_type_id');
            $table->string('break_down_type_code');
            $table->string('break_down_type_name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('break_down_types');
    }
};
