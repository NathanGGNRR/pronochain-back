<?php

namespace App\Enums;

enum FriendRequestsStatus: string
{
    case PENDING = 'pending';

    case ACCEPTED = 'accepted';

    case DECLINED = 'declined';
}
