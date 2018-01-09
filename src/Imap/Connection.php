<?php

namespace Endeavors\MaxMD\Message\Imap;

use Endeavors\MaxMD\Message\User;

class Connection
{
    public static function resolveInstance($args)
    {
        if( count($args) == 2 ) {
            return new AttachmentConnection();
        } elseif( count($args) >= 0 ) {
            return new MailConnection();
        }
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::resolveInstance($args);

        if (! $instance) {
            throw new \RuntimeException('A facade root has not been set.');
        }

        switch (count($args)) {
            case 0:
                return $instance->$method();
            case 1:
                return $instance->$method($args[0]);
            case 2:
                return $instance->$method($args[0], $args[1]);
            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);
            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }
}