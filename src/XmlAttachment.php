<?php

namespace Endeavors\MaxMD\Message;

class XmlAttachment implements Contracts\IViewAttachment
{
    protected $attachment;

    public function __construct(Contracts\IViewAttachment $attachment)
    {
        $this->attachment = $attachment;
    }

    public function view()
    {
        return simplexml_load_string($this->attachment->view());
    }
}
