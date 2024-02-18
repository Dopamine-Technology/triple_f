<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Language;
use App\Models\Position;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\UserType;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(UserType::class);
            $table->string('title');
            $table->foreignIdFor(Position::class)->nullable();
            $table->foreignIdFor(UserType::class, 'targeted_type')->nullable();
            $table->json('languages')->nullable();
            $table->integer('from_experience')->default(0);
            $table->integer('to_experience')->default(0);
            $table->integer('from_age')->default(0);
            $table->integer('to_age')->default(0);
            $table->integer('from_height')->default(0);
            $table->integer('to_height')->default(0);
            $table->integer('from_weight')->default(0);
            $table->integer('to_weight')->default(0);
            $table->string('gender')->default('both');
            $table->string('foot')->default('both');
            $table->foreignIdFor(Country::class)->nullable();
            $table->foreignIdFor(City::class)->nullable();
            $table->text('requirements')->default('');
            $table->text('additional_info')->default('');
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
