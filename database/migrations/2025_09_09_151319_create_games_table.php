<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('number_of_players');
            $table->integer("turn_duration")->nullable();
            $table->integer("round_duration");
            $table->integer("rounds");
            $table->string("ending");
            $table->integer('min_points');
            $table->integer('max_points');
            $table->boolean("has_turns");
            $table->boolean("has_teams");
            $table->integer("min_team_length");
            $table->integer("max_team_length");
            $table->foreign('user_id')->references('id')->on('users');
            $table->longText("rules")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
