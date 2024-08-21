<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id('area_id');
            $table->string('area_code',100);
            $table->string('area_name',100);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('areas')->insert([
            'area_code' => 'East',
            'area_name' => 'East'
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
