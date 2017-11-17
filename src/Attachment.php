<?php

namespace Endeavors\MaxMD\Message;

class Attachment implements Contracts\IAttachment
{
    protected $attachment;

    public function __construct($response)
    {
        $this->attachment = NullableAttachment::null();

        if( is_object($response) ) {
            $this->attachment = $response;
        }
    }

    public function download()
    {
        if( $this->hasFilename() ) {
            $outstream = fopen("php://output",'w');
            fwrite($outstream, $this->attachment->content);

            header($this->attachment->contentType);
            header("Cache-Control: no-store, no-cache");
            header('Content-Disposition: attachment; filename="'. $this->attachment->filename .'"');
            
            fclose($outstream);

            die();
        }
    }

    public function filename()
    {
        $this->checkAttribute('filename');

        return $this->attachment->filename;
    }

    public function contentType()
    {
        return $this->attachment->contentType;
    }

    public function content()
    {
        return $this->attachment->content;
    }

    public function hasFilename()
    {
        return null !== $this->filename();
    }

    protected function checkAttribute($attribute)
    {
        if( ! property_exists($this->attachment, $attribute) ) {
            $this->attachment = NullableAttachment::null();
        }
    }
}

class NullableAttachment
{
    final public static function null()
    {
        return new NullableAttachment;
    }

    public function __get($arg)
    {
        return null;
    }
}