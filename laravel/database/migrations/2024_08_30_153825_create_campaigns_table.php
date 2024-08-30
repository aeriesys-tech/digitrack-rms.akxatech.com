<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id('campaign_id');
            $table->foreignId('asset_id');
            $table->string('datasource');
            $table->string('file');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('asset_id')->references('asset_id')->on('assets');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
