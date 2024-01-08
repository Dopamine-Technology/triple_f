<?php

use App\Models\Challenge;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Challenge::class)->nullable();
            $table->string('title')->default('');
            $table->string('description')->default('');
            $table->string('video')->default('');
            $table->string('image')->default('');
            $table->boolean('approved')->default(false);
            $table->integer('shares')->default(0);
            $table->integer('saves')->default(0);
            $table->integer('total_points')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
