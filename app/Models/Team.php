<?php

namespace App\Models;

use App\Enums\Division;
use App\Enums\GameType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Team
 *
 * @property int $id
 * @property string $name
 * @property string $division
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read int $score
 * @method static \Database\Factories\TeamFactory factory(...$parameters)
 * @method static Builder|Team newModelQuery()
 * @method static Builder|Team newQuery()
 * @method static \Illuminate\Database\Query\Builder|Team onlyTrashed()
 * @method static Builder|Team query()
 * @method static Builder|Team whereCreatedAt($value)
 * @method static Builder|Team whereDeletedAt($value)
 * @method static Builder|Team whereDivision($value)
 * @method static Builder|Team whereId($value)
 * @method static Builder|Team whereName($value)
 * @method static Builder|Team whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Team withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Team withoutTrashed()
 * @mixin Eloquent
 */
class Team extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'division',
    ];

    protected function division(): Attribute
    {
        return Attribute::make(
            get: static fn($value, $attributes) => Division::from($value),
            set: static fn(Division $value) => $value->value,
        );
    }

    /** @noinspection PhpUnused */
    public function getScoreAttribute(): int
    {
        $score = 0;

        foreach($this->games()->where('type', GameType::GROUP)->get() as $game) {
            if (is_null($game->winner)) {
                // за ничью - 1 балл
                $score++;
            } else if ($game->winner && $game->winner->id === $this->id) {
                // за победу - 3 балла
                $score += 3;
            }
            // за проигрыш - 0 баллов
        }

        return $score;
    }

    /** @noinspection PhpUnused */
    public function games(): Builder|Game
    {
        return Game::where(function ($query) {
            $query->where('participant_a', $this->id)
                ->orWhere('participant_b', $this->id);
        });
    }

    public function gamesWithTeam(Team $team, ?GameType $type = null): Builder|Game
    {
        $games = $this->games()->where(function ($query) use ($team) {
            $query->where('participant_a', $team->id)
                ->orWhere('participant_b', $team->id);
        });

        if ($type) {
            /** @noinspection StaticInvocationViaThisInspection */
            $games->whereType($type->value);
        }

        return $games;
    }

    public static function getTeamsByDivisions(): array
    {
        $teams = [];

        foreach(Division::cases() as $division) {
            $teams[$division->name] = self::whereDivision($division)->get();
        }

        return $teams;
    }
}
