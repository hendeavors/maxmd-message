<?php

namespace Endeavors\MaxMD\Message;

use Endeavors\Support\VO\ModernArray;
use Endeavors\MaxMD\Support\Client;

class Messages implements Contracts\IMessages
{
    protected $messages;

    protected $perPage;

    private function __construct($response)
    {
        if( ! property_exists($response,'messages') ) {
            $this->messages = ModernArray::create([]);
        } elseif( is_object($response->messages) ) {
            $this->messages = ModernArray::create([$response->messages]);
        } else {
            $this->messages = ModernArray::create($response->messages);
        } 
    }

    public static function create($messages)
    {
        return new static($messages);
    }
    
    /**
     * Creating the array of objects adds a little overhead, but more stability
     * 
     * @return array of message details
     */
    public function All()
    {
        $messages = [];
        
        foreach( $this->messages->get() as $message ) {
            $messages[] = new MessageDetail($message);
        }

        return $messages;
    }

    public function Count()
    {
        return count($this->All());
    }

    public function Paginate($perPage = 25)
    {
        $items = $this->All();

        $paginator = Paginator::create($items, count($items), $perPage);

        return $paginator;
    }
    
    /**
     * the id must be of the type of the long uid
     * @todo consider the null object pattern here
     */
    public function View($id = null)
    {
        foreach($this->All() as $message) {
            if( $message->uid === (int)$id ) {
                return $message;
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