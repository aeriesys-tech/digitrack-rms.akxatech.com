<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('field_name', 100);
            $table->string('display_name', 100);
            $table->string('field_type', 50);
            $table->text('field_values')->nullable();
            $table->integer('field_length');
            $table->boolean('is_required')->default(0);
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_attributes');
    }
};
