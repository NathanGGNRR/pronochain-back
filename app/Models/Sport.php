<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Sport extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
    ];

    protected $appends = [
        'name',
    ];

    /**
     * Get the leagues for the sport.
     */
    public function leagues(): HasMany
    {
        return $this->hasMany(League::class);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => __('sports.'.Str::lower($this->code))
        )->shouldCache();
    }
}
