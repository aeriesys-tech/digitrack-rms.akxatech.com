<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_zones', function (Blueprint $table) {
            $table->id('template_zone_id');
            $table->foreignId('asset_template_id');
            $table->string('zone_name',100);
            $table->decimal('height')->nullable();
            $table->decimal('diameter')->nullable();
            $table->timestamps();
            $table->foreign('asset_template_id')->references('asset_template_id')->on('asset_templates');
            $table->index('asset_template_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_zones');
    }
};
