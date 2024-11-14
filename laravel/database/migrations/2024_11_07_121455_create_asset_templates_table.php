<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_templates', function (Blueprint $table) {
            $table->id('asset_template_id');
            $table->string('template_code',100);
            $table->string('template_name',100);
            $table->foreignId('asset_type_id');
            $table->string('latitude',255)->nullable();
            $table->string('longitude',255)->nullable();
            $table->string('radius',255)->nullable();
            $table->foreignId('plant_id');
            $table->foreignId('section_id')->nullable();
            $table->foreignId('area_id')->nullable();
            $table->integer('no_of_zones');
            $table->foreignId('functional_id')->nullable();
            $table->string('geometry_type',255)->nullable();
            $table->decimal('height')->nullable();
            $table->decimal('diameter')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('plant_id')->references('plant_id')->on('plants');
            $table->foreign('asset_type_id')->references('asset_type_id')->on('asset_type');
            $table->foreign('area_id')->references('area_id')->on('areas');
            $table->foreign('section_id')->references('section_id')->on('sections');
            $table->foreign('functional_id')->references('functional_id')->on('functionals');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_templates');
    }
};
