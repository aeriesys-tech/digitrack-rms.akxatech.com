<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clusters', function (Blueprint $table) {
            $table->id('cluster_id');
            $table->string('cluster_code',100);
            $table->string('cluster_name',100);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('clusters')->insert([
            'cluster_code' => 'East',
            'cluster_name' => 'East'
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('clusters');
    }
};
