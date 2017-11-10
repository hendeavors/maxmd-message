<?php

namespace Endeavors\MaxMD\Message;

use Endeavors\Support\VO\ModernArray;
use Endeavors\MaxMD\Support\Client;

class Messages implements Contracts\IMessages
{
    protected $messages;

    private function __construct($response)
    {
        $this->messages = ModernArray::create($response->messages);
    }

    public static function create($messages)
    {
        return new static($messages);
    }

    public function All()
    {
        return $this->messages->get();
    }
    
    /**
     * the id must be of the type of the long uid
     * @todo consider the null object pattern here
     */
    public function View($id = null)
    {
        foreach($this->All() as $message) {
            if( $message->uid === (int)$id ) {
                return new MessageDetail($message);
            }
        }
        
        return MessageDetail::null();
    }

    final protected function user()
    {
        $user = User::getInstance();

        return $user->ToArray();
    }
}