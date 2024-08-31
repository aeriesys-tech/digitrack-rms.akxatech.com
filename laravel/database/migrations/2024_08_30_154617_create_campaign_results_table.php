<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_results', function (Blueprint $table) {
            $table->id('campaign_result_id');
            $table->foreignId('campaign_id');
            $table->foreignId('asset_id');
            $table->string('location');
            $table->timestamp('date');
            $table->string('file');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('campaign_id')->references('campaign_id')->on('campaigns');
            $table->foreign('asset_id')->references('asset_id')->on('assets');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_results');
    }
};
