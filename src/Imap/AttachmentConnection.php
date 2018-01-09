<?php

namespace Endeavors\MaxMD\Message\Imap;

use Endeavors\MaxMD\Message\User;

class AttachmentConnection
{
    const IMAP_PATH = '{rs5.max.md:993/imap/ssl/novalidate-cert}';

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public function make(...$args)
    {
        $folder = null;

        if( isset($args[0]) ) {
            $folder = $args[0];
        }

        $connection = new \PhpImap\Mailbox($this->getFullPath($folder), User::getInstance()->getUsername(),  User::getInstance()->getPassword(), $args[1]);

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