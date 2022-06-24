<?php

namespace App\Models;

use App\Enums\GameRound;
use App\Enums\GameType;
use Database\Factories\GameFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game
 *
 * @property int            $id
 * @property int            $participant_a
 * @property int            $participant_b
 * @property int            $score_a
 * @property int            $score_b
 * @property string         $date
 * @property string         $type
 * @property GameRound|null $round
 * @property Carbon|null    $created_at
 * @property Carbon|null    $updated_at
 * @property Carbon|null    $deleted_at
 * @property-read Team|null $winner
 * @property-read Team|null $looser
 * @property-read Team|null $participantA
 * @property-read Team|null $participantB
 * @method static GameFactory factory(...$parameters)
 * @method static Builder|Game newModelQuery()
 * @method static Builder|Game newQuery()
 * @method static \Illuminate\Database\Query\Builder|Game onlyTrashed()
 * @method static Builder|Game query()
 * @method static Builder|Game whereCreatedAt($value)
 * @method static Builder|Game whereDate($value)
 * @method static Builder|Game whereDeletedAt($value)
 * @method static Builder|Game whereGameRound($value)
 * @method static Builder|Game whereId($value)
 * @method static Builder|Game whereParticipantA($value)
 * @method static Builder|Game whereParticipantB($value)
 * @method static Builder|Game whereScoreA($value)
 * @method static Builder|Game whereScoreB($value)
 * @method static Builder|Game whereType($value)
 * @method static Builder|Game whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Game withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Game withoutTrashed()
 * @mixin Eloquent
 */
class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'participant_a',
        'participant_b',
        'score_a',
        'score_b',
        'date',
        'type',
        'round',
    ];

    protected $appends = [
        'winner',
        'loser',
    ];

    /** @noinspection PhpUnused */
    public function getWinnerAttribute(): ?Team
    {
        if ($this->score_a > $this->score_b) {
            return $this->participantA;
        }

        if ($this->score_b > $this->score_a) {
            return $this->participantB;
        }

        return null;
    }

    /** @noinspection PhpUnused */
    public function getLooserAttribute(): ?Team
    {
        if ($this->score_a < $this->score_b) {
            return $this->participantA;
        }

        if ($this->score_b < $this->score_a) {
            return $this->participantB;
        }

        return null;
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: static fn($value, $attributes) => GameType::from($value),
            set: static fn(GameType $value) => $value->value,
        );
    }

    protected function round(): Attribute
    {
        return Attribute::make(
            get: static fn($value, $attributes) => GameRound::from($value),
            set: static fn(?GameRound $value) => $value->value ?? null,
        );
    }

    /** @noinspection PhpUnused */
    public function teams(): Builder|\Illuminate\Database\Query\Builder
    {
        return Team::whereIn('id', [
            $this->participant_a,
            $this->participant_b,
        ]);
    }

    /** @noinspection PhpUnused */
    public function participantA(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'participant_a', 'id');
    }

    /** @noinspection PhpUnused */
    public function participantB(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'participant_b', 'id');
    }
}
