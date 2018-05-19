<?php

namespace Endeavors\MaxMD\Message;

/**
 * To send an attachment, lets create a "binary file"
 */
class BinaryFile implements Contracts\IBinaryFile
{
    protected $attachment;

    public function __construct(Contracts\IAttachment $attachment)
    {
        $this->attachment = $attachment;
    }

    public function getContent(): string
    {
        return $this->attachment->content();
    }

    public function getContentType(): string
    {
        return $this->attachment->contentType();
    }

    public function getFileName(): string
    {
        return $this->attachment->filename();
    }

    public function toArray()
    {
        return [
            "content" => $this->getContent(),
            "contentType" => $this->getContentType(),
            "filename" => $this->getFileName()
        ];
    }
}
