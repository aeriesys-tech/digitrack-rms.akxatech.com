<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id('plant_id');
            $table->string('plant_code',100);
            $table->string('plant_name',100);
            $table->foreignId('cluster_id');
            $table->timestamps();
            $table->foreign('cluster_id')->references('cluster_id')->on('clusters');
            $table->softDeletes();
        });

        DB::table('plants')->insert([
            'plant_code' => 'JSW Steal Dolvi',
            'plant_name' => 'JSW Steal Dolvi',
            'cluster_id' => 1
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
