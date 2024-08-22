<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accessory_types', function (Blueprint $table) {
            $table->id('accessory_type_id');
            $table->string('accessory_type_code');
            $table->string('accessory_type_name');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accessory_types');
    }
};
