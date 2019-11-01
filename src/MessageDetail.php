<?php

namespace Endeavors\MaxMD\Message;

use Endeavors\MaxMD\Support\Client;
use Endeavors\Support\VO\ModernArray;
use Endeavors\SqlServer\Moment;

class MessageDetail implements Contracts\IMessageDetail
{
    use Moment\DateTimeConversion;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;

        if( ! is_object($message) || $message === null ) {
            $this->message = static::null();
        }
    }

    public static function null()
    {
        return new NullableMessageDetail();
    }

    public function originalMessage()
    {
        return $this->message;
    }

    public function uid()
    {
        return $this->id;
    }

    public function id()
    {
        return $this->message->id;
    }

    public function sender()
    {
        return $this->message->headers->fromaddress;
    }

    public function body()
    {
        return $this->message->textHtml;
    }

    public function text()
    {
        return strip_tags($this->body);
    }

    public function subject()
    {
        try {
            if( null === $this->message->subject ) {
                return '(no subject)';
            }

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
        if( null === $this->message ) {
            return new \DateTime('now');
        } elseif( null === $this->message->date ) {
            return new \DateTime('now');
        } elseif(is_object($this->message->date)) {
            return $this->message->date;
        } 

        return new \DateTime($this->message->date);
    }
    
    /**
     * The message sent date
     * 
     * @return datetime
     */
    public function sentDate()
    {
        if(is_object($this->message->date)) {
            return $this->message->date;
        } 

        return new \DateTime($this->message->date);
    }
    
    /**
     * The offset of the sent date or received date
     * @todo move the timezone conversion to a separate package for reuse
     */
    public function timeZoneOffset()
    {
        if( strtolower($this->folder) === "inbox.sent" ) {
            $offset = $this->sentDate()->getOffset() / 60 / 60;
        }

        $offset = $this->receivedDate()->getOffset() / 60 / 60;

        return $offset;
    }
    
    /**
     * The detail date time
     */
    public function detailDateTime()
    {
        if( strtolower($this->folder) === "inbox.sent" ) {
            return $this->sentDate()->format('D M Y') . ' at ' . $this->sentDate()->format('H:i:s A');
        }
        
        return $this->receivedDate()->format('D M Y') . ' at ' . $this->receivedDate()->format('H:i:s A');
    }
    
    /**
     * A carbon safe datetime.
     */
    public function carbonDateTime()
    {
        if( strtolower($this->folder) === "inbox.sent" ) {
            return $this->fromDateTime($this->sentDate());
        }
        
        return $this->fromDateTime($this->receivedDate());
    }

    public function headers()
    {
        return $this->message->headers;
    }

    public function replyTo()
    {
        return $this->message->replyTo;
    }

    public function hasAttachments()
    {
        return count($this->message->getAttachments()) > 0;
    }
    
    /**
     * deprecated
     */
    public function attachments()
    {
        $attachments = [];

        $attachmentAttributes = [];

        $attributesToFill = [
            'content',
            'contentType',
            'filename'
        ];
        
        if( isset($this->message->attachments) ) {
            foreach(ModernArray::create($this->message->attachments)->get() as $attachment) {
                if( is_string($attachment) ) {
                    
                    $attachmentAttributes[] = $attachment;
                } else {
                    $attachments[] = new Attachment($attachment);
                }

                if( count($attachmentAttributes) == 3 ) {
                    $attachment = array_combine($attributesToFill, $attachmentAttributes);
                    $attachments[] = new Attachment((object)$attachment);
                }
            }
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

    public function markRead()
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

    public static function null()
    {
        return null;
    }

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

    protected function receivedDate()
    {
        return new \DateTime('now');
    }

    final public function markRead()
    {

    }

    public function attachments()
    {
        $attachments = new Attachments(ModernArray::create([]));
        
        return $attachments;
    }
}