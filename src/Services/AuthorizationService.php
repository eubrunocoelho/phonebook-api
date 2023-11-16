<?php

namespace Services;

class AuthorizationService
{
    public static function checkOwner($userId, $ownerId)
    {
        return $userId === $ownerId;
    }
}
