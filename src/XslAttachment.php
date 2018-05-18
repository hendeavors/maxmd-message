<?php

namespace Endeavors\MaxMD\Message;

class XslAttachment implements Contracts\IViewAttachment
{
    protected $attachment;

    protected $styleSheetPath = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'cda.xsl';

    public static function fromXml($response)
    {
        return new static(new XmlAttachment(new Attachment($response)));
    }

    public static function fromPlainText($response)
    {
        return new static(new PlainAttachment(new Attachment($response)));
    }

    /**
     * Once again, we could consider privatizing? this and perform
     * the logic in the respective static construction
     * @param ContractsIViewAttachment $attachment [description]
     */
    public function __construct(Contracts\IViewAttachment $attachment)
    {
        $this->attachment = $attachment;
    }

    public function getStylesheetPath()
    {
        if ( ! file_exists($this->styleSheetPath) ) {
            throw new Exceptions\StyleSheetNotFoundException(sprintf("The stylesheet %s could not be found", $path));
        }

        return $this->styleSheetPath;
    }

    /**
     * Alias of display. Uses the default xsl stylesheet
     * @throws Exceptions\StyleSheetNotFoundException
     * @return string
     */
    public function view()
    {
        $displayable = false;

        $content = '';

        try {
            $xsl = new \DOMDocument;
            $xsl->load($this->getStylesheetPath());

            $xml = $this->attachment->view();

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

    public function setStyleSheetPath(string $path): Contracts\IViewAttachment
    {
        $this->styleSheetPath = $path;

        return $this;
    }
}
