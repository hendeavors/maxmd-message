<?php

namespace Endeavors\MaxMD\Message;
use Endeavors\MaxMD\Message\Contracts\IViewAttachment;

class MobileCda implements Contracts\IViewAttachment
{
    const STYLE_SHEET_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'mobilecda.xsl';

    protected $renderer;

    protected $attachment;

    public function __construct(Contracts\IRenderer $renderer, Contracts\IViewAttachment $attachment)
    {
        $this->renderer = $renderer;

        $this->attachment = $attachment;
    }

    /**
     * We'll use the default mobile cda path in this factory creation
     * @param  [type] $response - the response from retrieving a mailbox attachment
     * @return [type]           [description]
     */
    public static function create($response)
    {
        return new static(new XslRenderer(static::STYLE_SHEET_PATH), new XmlAttachment(new PlainAttachment($response)));
    }

    public function view(): string
    {
        $content = '';

        try {
            $content = $this
            ->renderer
            ->setRenderingContent($this->attachment->view())
            ->render();
        } catch(\ErrorException $ex) {
            // utilize some sort of logger or callback for the developer?
        } finally {
            return $content;
        }
    }

    public function __toString()
    {
        return $this->view();
    }
}
