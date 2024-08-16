<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checks', function (Blueprint $table) {
            $table->id('check_id');
            $table->string('field_name',255);
            $table->string('field_type',50);
            $table->string('default_value',100);
            $table->boolean('is_required')->default(false);
            $table->double('lcl',15,2)->nullable();
            $table->double('ucl',15,2)->nullable();
            $table->text('field_values')->nullable();
            $table->integer('order');
            $table->string('frequency');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checks');
    }
};
