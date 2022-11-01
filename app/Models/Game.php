<?php

namespace App\Models;

use App\Filters\GameFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'referee',
        'date',
        'league_id',
        'home_team_id',
        'away_team_id',
        'sport_id',
    ];

    /**
     * Get the league associated with the game.
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    /**
     * Get the away team associated with the game.
     */
    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    /**
     * Get the home team associated with the game.
     */
    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    /**
     * Get the sport associated with the game.
     */
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    /**
     * Get the odds for the game.
     */
    public function odds(): HasMany
    {
        return $this->hasMany(Odd::class);
    }

    public function scopeFilter(Builder $builder, Request $request): Builder
    {
        return (new GameFilter($request))->filter($builder);
    }
}
