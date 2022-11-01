<?php

namespace App\Services;

use App\Enums\FriendRequestsStatus;
use App\Models\User;

class FriendManager
{
    public function userRequestsFriend(User $requesting_user, User $requested_user): void
    {
        if ($requested_user->isWaitingForAnswerFrom($requesting_user)) {
            $requesting_user->accept($requested_user);

            return;
        }

        if ($requested_user->alreadyHasDeclinedFriendRequestFrom($requesting_user)) {
            $requesting_user->sendNewFriendRequestTo($requested_user);

            return;
        }

        $requesting_user->ask($requested_user);
    }

    public function answerFriendRequest(User $requested_user, User $requesting_user, FriendRequestsStatus $status): void
    {
        match ($status) {
            FriendRequestsStatus::ACCEPTED => $requested_user->accept($requesting_user),
            FriendRequestsStatus::DECLINED => $requested_user->decline($requesting_user),
        };
    }
}
