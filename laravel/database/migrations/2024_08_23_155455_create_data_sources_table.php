<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_sources', function (Blueprint $table) {
            $table->id('data_source_id');
            $table->foreignId('data_source_type_id');
            $table->string('data_source_code',100);
            $table->string('data_source_name',100);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('data_source_type_id')->references('data_source_type_id')->on('data_source_types');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_sources');
    }
};
