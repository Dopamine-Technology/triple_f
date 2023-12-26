<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->boolean('approved_by_admin')->default(false);
        });
        Schema::table('coaches', function (Blueprint $table) {
            $table->boolean('approved_by_admin')->default(true);
        });
        Schema::table('scouts', function (Blueprint $table) {
            $table->boolean('approved_by_admin')->default(true);
        });
        Schema::table('talents', function (Blueprint $table) {
            $table->boolean('approved_by_admin')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn('approved_by_admin');
        });
        Schema::table('coaches', function (Blueprint $table) {
            $table->dropColumn('approved_by_admin');
        });
        Schema::table('scouts', function (Blueprint $table) {
            $table->dropColumn('approved_by_admin');
        });
        Schema::table('talent', function (Blueprint $table) {
            $table->dropColumn('approved_by_admin');
        });
    }
};
