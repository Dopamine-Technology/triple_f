<?php

use App\Models\Country;
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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Sport::class);
            $table->foreignIdFor(Country::class);
            $table->string('mobile_number');
            $table->integer('year_founded')->nullable();
            $table->boolean('is_authorized')->default(false);
            $table->string('registration_document')->nullable();
            $table->integer('follower_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clubs');
    }
};
