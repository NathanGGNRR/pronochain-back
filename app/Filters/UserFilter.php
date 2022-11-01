<?php

namespace App\Filters;

class UserFilter extends AbstractFilter
{
    protected array $filters = [
        'username' => UsernameFilter::class,
    ];
}
