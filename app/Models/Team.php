<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'logo',
        'country_id',
        'sport_id',
    ];

    /**
     * Get the country associated with the team.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the sport associated with the team.
     */
    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }
}
