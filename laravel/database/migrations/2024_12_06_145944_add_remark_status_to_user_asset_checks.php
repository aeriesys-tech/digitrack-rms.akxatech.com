<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_asset_checks', function (Blueprint $table) {
            $table->unsignedBigInteger('remark_user_id')->nullable()->after('value');
            $table->foreign('remark_user_id')->references('user_id')->on('users');
            $table->boolean('remark_status')->default(false)->after('remark_user_id');
            $table->text('remarks')->nullable()->after('remark_status');
        });
    }

    public function down(): void
    {
        Schema::table('user_asset_checks', function (Blueprint $table) {
            $table->dropForeign(['remark_user_id']);
            $table->dropColumn('remark_user_id');
            $table->dropColumn('remark_status');
            $table->dropColumn('remarks');
        });
    }
};
