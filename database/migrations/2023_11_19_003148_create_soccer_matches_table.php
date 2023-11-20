<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('soccer_matches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('dayOfMatch');
            $table->foreignId('team_local_id')->constrained('teams');
            $table->foreignId('team_visit_id')->constrained('teams');
            $table->foreignId('referee_id')->constrained('users');
            $table->integer('team_local_goals')->default(0);
            $table->integer('team_visit_goals')->default(0);
            $table->integer('team_local_fouls')->default(0);
            $table->integer('team_visit_fouls')->default(0);
            $table->boolean('started')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soccer_matches');
    }
};
