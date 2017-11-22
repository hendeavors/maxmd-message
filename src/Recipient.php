<?php

namespace Endeavors\MaxMD\Message;

use Endeavors\Support\VO;

/**
 * A recipient should have an email and a type
 * @todo validation
 */
class Recipient
{
    protected $recipient;

    public function __construct($recipient)
    {
        $recipient = VO\ModernArray::create($recipient);

        if( ! $recipient->hasKey('email') || ! $recipient->hasKey('type') ) {
            throw new \InvalidArgumentException("A recipient must have an email and a type (TO|CC|BCC)");
        }

        $this->recipient = $recipient->get();
    }

    public function email()
    {
        return $this->recipient['email'];
    }

    public function type()
    {
        return $this->recipient['type'];
    }

    public function __get($arg)
    {
        if(method_exists($this, $arg)) {
            return $this->$arg();
        }
    }
}