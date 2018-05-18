<?php

namespace Endeavors\MaxMD\Message;

class ImapAttachment implements Contracts\IAttachment
{
    protected $attachment;

    public function __construct($response)
    {
        $this->attachment = NullableAttachment::null();

        if( is_object($response) ) {
            $this->attachment = $response;
        }
    }

    public function download(Contracts\IDownloadAttachment $downloader = null)
    {
        $downloadable = $downloader ?? new DownloadableAttachment($this);

        $downloadable->download();
    }

    public function id()
    {
        return $this->attachment->id;
    }

    public function filename()
    {
        $this->checkAttribute('name');

        return $this->attachment->name;
    }

    public function contentType()
    {
        return finfo_file($this->attachment->filePath);
    }

    public function content()
    {
        return file_get_contents($this->attachment->filePath);
    }

    public function filePath()
    {
        return $this->attachment->filePath;
    }

    public function relativeFilePath()
    {
        return $this->attachment->relativeFilePath;
    }

    /**
     * Alias of display. Uses the default xsl stylesheet
     * @throws Exceptions\StyleSheetNotFoundException
     * @return string
     */
    public function view()
    {
        return StandardCda::create($this);
    }

    public function mobileView()
    {
        return MobileCda::create($this);
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

    public function toArray()
    {
        return [
            'filename' => $this->filename(),
            'filepath' => $this->attachment->filePath,
            'id' => $this->id()
        ];
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
