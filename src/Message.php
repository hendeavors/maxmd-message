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

                if(count($this->binaryFilesList) > 0) {
                    $request["attachmentList"] = $this->binaryFilesList;
                }

                $this->response = Client::DirectMessage()->Send(['sendRequest' => $request]);
            }

            return $this;
        }

        // throw exception
        throw new Exceptions\InvalidMessageException("The message must have a sender, htmlBody true or false, and recipients");
    }

    protected $binaryFilesList = [];

    public function addAttachment(string $filePath)
    {
        // public $id;
    	// public $contentId;
    	// public $name;
    	// public $filePath;
    	// public $disposition;
        $outgoingFile = new Imap\OutgoingMailAttachment();
        $outgoingFile->name = basename($filePath);
        $outgoingFile->filePath = $filePath;
        $outgoingFile->disposition = "attachment";
        $this->binaryFilesList[] = (new BinaryFile(new ImapAttachment($outgoingFile)))->toArray();

        return $this;
    }

    /**
     * @throws Endeavors\MaxMD\Message\Exceptions\InvalidFHIRQueryException, Endeavors\MaxMD\Message\Exceptions\InvalidResourceException
     */
    public function SendFHIRQuery()
    {
        if( $this->message->hasKey('resources') && $this->message->hasKey('recipients') ) {

            if( $this->validateRecipients($this->message->get()['recipients']) && $this->validateResources($this->message->get()['resources']) ) {

                $request = [
                    "auth" => $this->user(),
                    "query" => [
                        "recipients" => $this->message->get()['recipients'],
                        "resources" => $this->message->get()['resources'],
                        "subject" => '(no subject)'
                    ]
                ];

                if( $this->message->hasKey('subject') ) {
                    $request['query']['subject'] = $this->message->get()['subject'];
                }

                $this->response = Client::DirectMessage()->PatientFHIRQuery($request);

                return $this;
            }
        }

        // throw exception
        throw new Exceptions\InvalidFHIRQueryException("The FHIR Query must have recipients and resources");
    }

    public function Success()
    {
        return null !== $this->response ? $this->response->return->success : false;
    }

    public function message()
    {
        return null !== $this->response ? $this->response->return->message : "Something went wrong sending your message";
    }

    protected function validateResources($resources)
    {
        $validResources = [];

        foreach($resources as $resource) {
            $newResource = FHIRResourceType::create($resource);

            $validResources[] = $newResource->toArray();
        }

        $newMessage = $this->message->get();

        $newMessage['resources'] = $validResources;

        $this->message = ModernArray::create($newMessage);

        return count($validResources) > 0;
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

    /**
     * Remove the recipient from the recipients list from the message
     * And set the message with a new recipients list
     */
    protected function removeRecipient($recipient)
    {
        $recipients = $this->message->get()['recipients'];

        foreach($recipients as $key => $value) {
            $email = is_object($value) ? $value->email : $value['email'];

            if( $email === $recipient->email )
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
