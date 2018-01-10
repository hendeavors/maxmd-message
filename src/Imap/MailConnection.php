<?php

namespace Endeavors\MaxMD\Message\Imap;

use Endeavors\MaxMD\Message\User;

class MailConnection
{
    const IMAP_PATH = '{rs5.max.md:993/imap/ssl/novalidate-cert}';

    public function make(...$args)
    {
        $folder = null;

        if( isset($args[0]) ) {
            $folder = $args[0];
        }

        $connection = new Mailbox($this->getFullPath($folder), User::getInstance()->getUsername(),  User::getInstance()->getPassword());

        return $connection;
    }

    private function getFullPath($folder = null)
    {
        $path = self::IMAP_PATH;
        
        if( null !== $folder ) {
            $path .= $folder;
        }

        return $path;
    }
}