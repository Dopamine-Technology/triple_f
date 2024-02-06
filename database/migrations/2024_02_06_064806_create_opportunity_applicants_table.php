<?php

use App\Models\Opportunity;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('opportunity_applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Opportunity::class);
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(UserType::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunity_applicants');
    }
};
