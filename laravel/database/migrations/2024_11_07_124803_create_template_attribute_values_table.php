<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_attribute_values', function (Blueprint $table) {
            $table->id('template_attribute_value_id');
            $table->foreignId('asset_template_id');
            $table->foreignId('asset_attribute_id');
            $table->string('field_value');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->foreign('asset_attribute_id')->references('asset_attribute_id')->on('asset_attributes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_attribute_values');
    }
};
