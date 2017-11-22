<?php

namespace Endeavors\MaxMD\Message\Traits;

use Endeavors\MaxMD\Message\User;

trait UserTrait {

    final protected function user()
    {
        $user = User::getInstance();

        return $user->ToArray();
    }
}