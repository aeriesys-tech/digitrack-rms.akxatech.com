<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_source_types', function (Blueprint $table) {
            $table->id('data_source_type_id');
            $table->string('data_source_type_code',100);
            $table->string('data_source_type_name',100);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_source_types');
    }
};
