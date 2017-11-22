<?php

namespace Endeavors\MaxMD\Message;

use Endeavors\MaxMD\Support\Client;
use Endeavors\Support\VO\ModernArray;
use Endeavors\Support\VO\EmailAddress;
use Endeavors\SqlServer\Moment;

class Message implements Contracts\IMessageDetail
{
    use Moment\DateTimeConversion, Traits\UserTrait;

    protected $message;

    protected $response;

    protected $succeeds = false;

    protected $strict = false;

    private function __construct($message, $strict = false)
    {
        $this->strict = $strict;

        $this->message = ModernArray::create($message);
    }

    public static function create($message, $strict = false)
    {
        return new static($message, $strict);
    }

    public static function strict($message)
    {
        return new static($message, true);
    }

    public static function loose($message)
    {
        return new static($message, false);
    }
    
    /**
     * We are creating a message so we dont have an id yet
     */
    public function id()
    {
        return null;
    }

    public function body()
    {
        return $this->message->get()['body'];
    }

    public function sender()
    {
        return $this->message->get()['sender'];
    }

    public function subject()
    {
        return $this->message->get()['subject'];
    }

    public function Send()
    {
        if( $this->message->hasKey('sender') && $this->message->hasKey('htmlBody') && $this->message->hasKey('recipients') && $this->message->hasKey('body') ) {
            // send the message 
            if( $this->validateRecipients($this->message->get()['recipients']) ) {

                $request = [
                    "authentication" => $this->user(),
                    "message" => $this->message->get()
                ];
        
                $this->response = Client::DirectMessage()->Send(['sendRequest' => $request]);
            }
    
            return $this;
        }

        // throw exception
        throw new Exceptions\InvalidMessageException("The message must have a sender, htmlBody true or false, and recipients");
    }

    public function SendFHIRQuery()
    {
        if( $this->message->hasKey('resources') && $this->message->hasKey('recipients') ) {
            
            $this->validateResources($this->message->get()['resources']);

            $request = [
                "auth" => $this->user(),
                "query" => []
            ];
        }
        
    }

    public function Success()
    {
        return null !== $this->response ? $this->response->return->success : false;
    }

    protected function validateResources($resources)
    {

    }

    /**
     * Get a list of valid recipients
     * 
     * @return array
     */
    protected function validateRecipients($recipients)
    {
        // prevalidate the recipient
        foreach($recipients as $recipient) {
            $newRecipient = new Recipient($recipient);

            if( false === $this->strict ) {

                try {
                    EmailAddress::loose($newRecipient->email);
                } catch(\RuntimeException $ex) {
                    // remove recipient
                    $this->removeRecipient($newRecipient);
                }

            } else {
                EmailAddress::loose($newRecipient->email);
            }
        }

        return count($this->message->get()['recipients']) > 0;
    }

    protected function removeRecipient($recipient)
    {
        $recipients = $this->message->get()['recipients'];

        foreach($recipients as $key => $value) {
            if( $value['email'] === $recipient->email )
                unset($recipients[$key]);
        }

        $newMessage = $this->message->get();

        $newMessage['recipients'] = $recipients;

        $this->message = ModernArray::create($newMessage);
    }

    public function __get($arg)
    {
        $that = $this;

        if( method_exists($that, $arg) ) {
            return $that->$arg();
        }
    }
}