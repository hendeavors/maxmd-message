<?php

namespace Endeavors\MaxMD\Message;

class PlainAttachment implements Contracts\IViewAttachment
{
    protected $attachment;

    public function __construct(Contracts\IAttachment $attachment)
    {
        $this->attachment = $attachment;
    }

    public function view()
    {
        return $this->attachment->content();
    }
}
