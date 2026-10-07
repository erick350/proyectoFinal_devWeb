<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('matchdays', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('number')->unique();
            $table->date('game_date');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->restrictOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->string('photo_path')->nullable();
            $table->timestamps();
            $table->index(['team_id', 'last_name', 'first_name']);
        });

        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->foreignId('matchday_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('quantity');
            $table->timestamps();
            $table->unique(['player_id', 'matchday_id']);
        });

        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->text('description');
            $table->enum('card_type', ['amarilla', 'roja']);
            $table->date('incident_date');
            $table->date('suspension_until')->nullable();
            $table->timestamps();
            $table->index(['card_type', 'incident_date', 'suspension_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
        Schema::dropIfExists('goals');
        Schema::dropIfExists('players');
        Schema::dropIfExists('matchdays');
        Schema::dropIfExists('teams');
    }
};
