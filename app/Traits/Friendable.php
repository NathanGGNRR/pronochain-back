<?php

namespace App\Traits;

use App\Enums\FriendRequestsStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait Friendable
{
    public function accept(User $user): void
    {
        $this->answer($user, FriendRequestsStatus::ACCEPTED);
    }

    public function decline(User $user): void
    {
        $this->answer($user, FriendRequestsStatus::DECLINED);
    }

    public function alreadyHasDeclinedFriendRequestFrom(User $user): bool
    {
        return $this->declinedFriendRequests()->where('user_id', $user->id)->exists();
    }

    public function sendNewFriendRequestTo(User $user): void
    {
        $user->declinedFriendRequests()->updateExistingPivot($this->id, [
            'status' => FriendRequestsStatus::PENDING->value,
            'requests_count' => $user->declinedFriendRequests()->where('user_id', $this->id)->first()->pivot->requests_count + 1,
        ]);
    }

    public function ask(User $user): void
    {
        $this->friendsOfMine()->attach($user->id, ['status' => FriendRequestsStatus::PENDING->value, 'requests_count' => 1]);
    }

    public function isWaitingForAnswerFrom(User $user): bool
    {
        return $this->friendRequestsSent->contains($user);
    }

    public function friendsOfMine(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', FriendRequestsStatus::ACCEPTED->value)->withTimestamps();
    }

    public function friendOf(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'friends', 'friend_id', 'user_id')
            ->wherePivot('status', FriendRequestsStatus::ACCEPTED->value)->withTimestamps();
    }

    public function friendRequests(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'friends', 'friend_id', 'user_id')
            ->wherePivot('status', FriendRequestsStatus::PENDING->value)->withTimestamps();
    }

    public function friendRequestsSent(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', FriendRequestsStatus::PENDING->value)->withTimestamps();
    }

    public function declinedFriendRequests(): BelongsToMany
    {
        return $this->belongsToMany(__CLASS__, 'friends', 'friend_id', 'user_id')
            ->wherePivot('status', FriendRequestsStatus::DECLINED->value)->withTimestamps()->withPivot('requests_count');
    }

    private function answer(User $user, FriendRequestsStatus $status): void
    {
        $this->friendRequests()->updateExistingPivot($user->id, [
            'status' => $status->value,
        ]);
    }
}
