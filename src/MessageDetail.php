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
        try {
            return $this->message->subject;
        } catch(\Exception $ex) {
            return '(no subject)';
        }
    }

    public function recipients()
    {
        return $this->message->recipients;
    }

    public function folder()
    {
        return $this->message->folder;
    }

    protected function receivedDate()
    {
        if( null === $this->message->receivedDate ) {
            return new \DateTime('now');
        }
        elseif(is_object($this->message->receivedDate)) {
            return $this->message->receivedDate;
        } 

        return new \DateTime($this->message->receivedDate);
    }

    public function receivedAt()
    {
        return $this->receivedDate()->format("M d");
    }

    public function receivedTime()
    {
        return $this->receivedDate()->format("H:i");
    }

    public function receivedTimeZoneOffset()
    {
        $offset = $this->receivedDate()->getOffset() / 60 / 60;

        return $offset;
    }

    public function receivedDateOrTime()
    {
        if( $this->shouldDisplayReceivedDate()) {
            return $this->receivedAt();
        }

        return $this->receivedTime();
    }

    public function sentOrReceivedAtDateTime()
    {
        if( strtolower($this->folder) === "inbox.sent" ) {
            return $this->sentDate()->format('Y-m-d H:i:s');
        }
        
        return $this->receivedDate()->format('Y-m-d H:i:s');
    }

    public function detailDateTime()
    {
        if( strtolower($this->folder) === "inbox.sent" ) {
            return $this->sentDate()->format('D M Y') . ' at ' . $this->sentDate()->format('H:i:s A');
        }
        
        return $this->receivedDate()->format('D M Y') . ' at ' . $this->receivedDate()->format('H:i:s A');
    }

    public function carbonDateTime()
    {
        if( strtolower($this->folder) === "inbox.sent" ) {
            return substr($this->sentDate()->format('Y-m-d H:i:s.u'), 0, -3);
        }
        
        return substr($this->receivedDate()->format('Y-m-d H:i:s.u'), 0, -3);
    }

    public function sentOrReceivedAt()
    {
        if( strtolower($this->folder) === "inbox.sent" ) {
            return $this->sentAt() . ' ' . $this->sentTime();
        }
        
        return $this->receivedDateOrTime();
    }

    public function shouldDisplayReceivedDate()
    {
        $now = new \DateTime("now");

        $today = $now->format('m/d/y');

        $receivedDate = $this->receivedDate()->format('m/d/y');

        return $receivedDate < $today;
    }

    public function shouldDisplayReceivedTime()
    {
        return ! $this->shouldDisplayReceivedDate();
    }

    public function sentDate()
    {
        if(is_object($this->message->sentDate)) {
            return $this->message->sentDate;
        } 

        return new \DateTime($this->message->sentDate);
    }

    public function sentAt()
    {
        return $this->sentDate()->format("M d");
    }

    public function sentTime()
    {
        return $this->sentDate()->format("H:i");
    }

    public function sentTimeZoneOffset()
    {
        $offset = $this->sentDate()->getOffset() / 60 / 60;

        return $offset;
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
            'folder' => $this->folder(),
            'receivedAt' => $this->receivedAt(),
            'receivedTime' => $this->receivedTime()
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

    public function sentOrReceivedAt()
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