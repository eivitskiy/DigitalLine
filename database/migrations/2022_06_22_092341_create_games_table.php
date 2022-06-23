<?php

use App\Enums\GameRound;
use App\Enums\GameType;
use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('games', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class, 'participant_a');
            $table->foreignIdFor(Team::class, 'participant_b');
            $table->unsignedSmallInteger('score_a');
            $table->unsignedSmallInteger('score_b');
            $table->date('date');
            $table->enum('type', GameType::values());
            $table->enum('round', GameRound::values())->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
