<?php

namespace Endeavors\MaxMD\Message;

use Endeavors\MaxMD\Support\Client;
use Endeavors\Support\VO\ModernArray;

class MessageDetail implements Contracts\IMessageDetail
{
    protected $message;

    public function __construct($message)
    {
        $this->message = $message;

        if( ! is_object($message) || $message === null ) {
            $this->message = static::null();
        }

        if( null !== $this->message ) {
            $this->markRead();
        }
    }

    public static function null()
    {
        return new NullableMessageDetail();
    }

    public function uid()
    {
        return $this->id;
    }

    public function id()
    {
        return $this->message->uid;
    }

    public function sender()
    {
        return $this->message->sender;
    }

    public function body()
    {
        return $this->message->body;
    }

    public function text()
    {
        return strip_tags($this->body);
    }

    public function subject()
    {
        return $this->message->subject;
    }

    public function recipients()
    {
        return $this->message->recipients;
    }

    public function folder()
    {
        return $this->message->folder;
    }

    public function receivedAt()
    {
        return $this->message->receivedDate;
    }

    public function sentAt()
    {
        return $this->message->sentDate;
    }

    public function headers()
    {
        return $this->message->headers;
    }

    public function replyTo()
    {
        return $this->message->replyTo;
    }

    public function attachments()
    {
        $attachments = [];

        foreach(ModernArray::create($this->message->attachmentList)->get() as $attachment) {
            $attachments[] = new Attachment($attachment);
        }

        $attachments = new Attachments(ModernArray::create($attachments));

        return $attachments;
    }

    public function toArray()
    {
        return [
            'id' => $this->id(),
            'body' => $this->body(),
            'subject' => $this->subject(),
            'recipients' => $this->recipients(),
            'folder' => $this->folder()
        ];
    }

    public function __get($arg)
    {
        if( method_exists($this, $arg) ) {
            return $this->$arg();
        }
    }

    protected function markRead()
    {
        $request = [
            "auth" => $this->user(),
            "folderName" => $this->folder(),
            "uids" => [$this->id()]
        ];

        $response = Client::DirectMessage()->MarkMessagesAsReadByUID($request);
    }

    private function user()
    {
        return User::getInstance()->ToArray();
    }
}

class NullableMessageDetail extends MessageDetail
{
    public function __construct() {}

    public function id()
    {
        return 0;
    }

    public function uid()
    {
        return 0;
    }

    public function sender()
    {
        return '';
    }
    
    public function body()
    {
        return '';
    }
    
    public function subject()
    {
        return '';
    }
    
    public function text()
    {
        return '';
    }

    public function recipients()
    {
        return [];
    }

    public function folder()
    {
        return '';
    }

    public function receivedAt()
    {
        return '';
    }

    public function sentAt()
    {
        return '';
    }

    public function headers()
    {
        return '';
    }

    public function replyTo()
    {
        return '';
    }

    final protected function markRead()
    {

    }

    public function attachments()
    {
        $attachments = new Attachments(ModernArray::create([]));
        
        return $attachments;
    }
}