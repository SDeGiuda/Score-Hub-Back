<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->foreignId("creator_id")->constrained("users");
            $table->foreignId("game_id")->constrained("games");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_match');
    }
};
