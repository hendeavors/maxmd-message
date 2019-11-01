<?php

namespace Endeavors\MaxMD\Message;

use Endeavors\MaxMD\Message\Contracts;

class Attachments implements Contracts\IAttachments
{
    protected $attachments;

    public function __construct($response)
    {
        $this->attachments = $response;
    }
    
    /**
     * If the index is null we'll zip the files
     */
    public function download($index = null)
    {
        if( null !== $index && $this->attachments->hasKey($index) ) {
            $this->attachments->get()[$index]->download();
        }
    }

    public function view($index = null)
    {
        if( null !== $index && $this->attachments->hasKey($index) ) {
            return $this->attachments->get()[$index]->view();
        }
    }

    public function get()
    {
        return $this->attachments->get();
    }

    public function count()
    {
        return count($this->get());
    }
}