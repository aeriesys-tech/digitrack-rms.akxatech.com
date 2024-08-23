<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('break_down_lists', function (Blueprint $table) {
            $table->id('break_down_list_id');
            $table->foreignId('break_down_type_id');
            $table->string('break_down_list_code',100);
            $table->string('break_down_list_name',100);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('break_down_type_id')->references('break_down_type_id')->on('break_down_types');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('break_down_lists');
    }
};
