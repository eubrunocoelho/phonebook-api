<?php

namespace Services;

class AuthorizationService
{
    public static function checkOwner(int $userId, int $ownerId): bool
    {
        return $userId === $ownerId;
    }
}
