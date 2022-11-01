<?php

namespace App\Models;

use App\Filters\UserFilter;
use App\Traits\Friendable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use Friendable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'eth_address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'eth_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'friends' => AsCollection::class,
    ];

    public function scopeFilter(Builder $builder, $request)
    {
        return (new UserFilter($request))->filter($builder);
    }

    public function odds(): BelongsToMany
    {
        return $this->belongsToMany(Odd::class)->withTimestamps();
    }

    /**
     * Get the user's friends.
     */
    protected function friends(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->friendsOfMine->merge($this->friendOf)
        );
    }
}
