<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('break_down_list_asset_types', function (Blueprint $table) {
            $table->id('break_down_list_asset_type_id');
            $table->foreignId('break_down_list_id');
            $table->foreignId('asset_type_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_type_id')->references('asset_type_id')->on('asset_type');
            $table->foreign('break_down_list_id')->references('break_down_list_id')->on('break_down_lists');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('break_down_list_asset_types');
    }
};
