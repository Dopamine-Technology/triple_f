<?php

use App\Models\Sport;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('mobile_number');
            $table->foreignIdFor(Sport::class);
            $table->string('gender')->default('other');
            $table->date('birth_date')->nullable();
            $table->string('residence_place');
            $table->integer('years_of_experience')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaches');
    }
};
