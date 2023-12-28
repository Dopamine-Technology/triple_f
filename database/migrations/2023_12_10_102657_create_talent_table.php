<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Position;
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
        Schema::create('talents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('mobile_number');
            $table->foreignIdFor(Sport::class);
            $table->foreignIdFor(Position::class, 'parent_position_id')->nullable();
            $table->foreignIdFor(Position::class, 'position_id')->nullable();
            $table->string('gender')->default('other');
            $table->date('birth_date')->nullable();
            $table->integer('height')->default(160);
            $table->integer('wight')->default(60);
            $table->foreignIdFor(Country::class)->nullable();
            $table->foreignIdFor(City::class)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talents');
    }
};
