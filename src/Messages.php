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
        } else {
            $this->messages = ModernArray::create($response->messages);
        } 
    }

    public static function create($messages)
    {
        return new static($messages);
    }

    public function All()
    {
        return $this->messages->get();
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