<?php

namespace Endeavors\MaxMD\Message;

class DownloadableAttachment implements Contracts\IDownloadAttachment
{
    protected $attachment;

    public function __construct(Contracts\IAttachment $attachment)
    {
        $this->attachment = $attachment;
    }

    public function download()
    {
        if( $this->hasFilename() ) {
            $outstream = fopen("php://output",'w');
            fwrite($outstream, $this->attachment->content());

            header($this->attachment->contentType());
            header("Cache-Control: no-store, no-cache");
            header('Content-Disposition: attachment; filename="'. $this->attachment->filename() .'"');

            fclose($outstream);

            die();
        }
    }

    public function hasFilename()
    {
        return null !== $this->attachment->filename();
    }
}
