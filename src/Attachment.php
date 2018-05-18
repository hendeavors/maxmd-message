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
        $downloadable = new DownloadableAttachment($this);

        $downloadable->download();
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

    /**
     * Alias of display. Uses the default xsl stylesheet
     * @throws Exceptions\StyleSheetNotFoundException
     * @return string
     */
    public function view()
    {
        return $this->display();
    }

    /**
     * @throws Exceptions\StyleSheetNotFoundException
     * @return string|bool
     */
    public function display($path = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'cda.xsl')
    {
        $displayable = false;

        $content = '';

        if ( ! file_exists($path) ) {
            throw new Exceptions\StyleSheetNotFoundException(sprintf("The stylesheet %s could not be found", $path));
        }

        try {
            $xsl = new \DOMDocument;
            $xsl->load($path);

            $xml = simplexml_load_string($this->content());

            $proc = new \XSLTProcessor;
            $proc->importStyleSheet($xsl); // attach the xsl rules

            $content = $proc->transformToXML($xml);

            $displayable = true;
        } catch(\ErrorException $ex) {
            $displayable = false;
        } finally {
            return $displayable ? $content : $displayable;
        }
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
