<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_departments', function (Blueprint $table) {
            $table->id('template_department_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('department_id');
            $table->timestamps();
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('department_id')->references('department_id')->on('departments');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_departments');
    }
};
