<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_asset_checks')) {
            Schema::create('user_asset_checks', function (Blueprint $table) {
                $table->id('user_asset_check_id');
                $table->foreignId('user_check_id');
                $table->foreignId('check_id');
                $table->foreignId('asset_check_id');
                $table->string('field_name', 255);
                $table->string('field_type', 100);
                $table->string('default_value', 100);
                $table->boolean('is_required')->default(false);
                $table->double('lcl', 15, 2)->nullable();
                $table->double('ucl', 15, 2)->nullable();
                $table->text('field_values')->nullable();
                $table->integer('order');
                $table->string('value', 255)->nullable();
                $table->timestamps();

                $table->foreign('user_check_id')->references('user_check_id')->on('user_checks');
                $table->foreign('check_id')->references('check_id')->on('checks');
                $table->foreign('asset_check_id')->references('asset_check_id')->on('asset_checks');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_asset_checks');
    }
};
