<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_check_attachments', function (Blueprint $table) {
            $table->id('user_check_attachment_id');
            $table->foreignId('user_check_id');
            $table->string('attachments');
            $table->timestamps();
            $table->foreign('user_check_id')->references('user_check_id')->on('user_checks');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_check_attachments');
    }
};
