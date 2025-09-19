<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->foreignId("match_id")->constrained("matches");
            $table->integer("position");
            $table->string("status");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_results');
    }
};
