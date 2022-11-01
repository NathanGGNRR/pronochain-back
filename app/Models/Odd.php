<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Odd extends Model
{
    use HasFactory;

    protected $fillable = [
        'odd_type_id',
        'value',
        'game_id',
    ];

    /**
     * Get the type associated with the odd.
     */
    public function oddType(): BelongsTo
    {
        return $this->belongsTo(OddType::class);
    }
}
