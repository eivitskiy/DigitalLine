<?php

namespace App\Models;

use App\Enums\Division;
use Database\Factories\TeamFactory;
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
 * @property int         $id
 * @property string      $name
 * @property string      $division
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static TeamFactory factory(...$parameters)
 * @method static Builder|Team newModelQuery()
 * @method static Builder|Team newQuery()
 * @method static Builder|Team query()
 * @method static Builder|Team whereCreatedAt($value)
 * @method static Builder|Team whereDeletedAt($value)
 * @method static Builder|Team whereDivision($value)
 * @method static Builder|Team whereId($value)
 * @method static Builder|Team whereName($value)
 * @method static Builder|Team whereUpdatedAt($value)
 * @mixin Eloquent
 * @method static \Illuminate\Database\Query\Builder|Team onlyTrashed()
 * @method static \Illuminate\Database\Query\Builder|Team withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Team withoutTrashed()
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
    public function games(): Builder|Game
    {
        return Game::where(function ($query) {
            $query->where('participant_a', $this->id)
                ->orWhere('participant_b', $this->id);
        });
    }

    public function gamesWithTeam(Team $team): Builder|Game
    {
        return $this->games()->where(function ($query) use ($team) {
            $query->where('participant_a', $team->id)
                ->orWhere('participant_b', $team->id);
        });
    }
}
