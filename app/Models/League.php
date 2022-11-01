<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class League extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'is_enabled',
        'country_id',
        'sport_id',
    ];

    /**
     * Get the sport associated with the league.
     */
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    /**
     * Get the country associated with the league.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
