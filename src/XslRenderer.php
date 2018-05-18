<?php

namespace Endeavors\MaxMD\Message;

class XslRenderer implements Contracts\IRenderer
{
    protected $styleSheetPath = "";

    protected $content;

    /**
     * Once again, we could consider privatizing? this and perform
     * the logic in the respective static construction
     * @param ContractsIViewAttachment $attachment [description]
     */
    public function __construct(string $styleSheetPath)
    {
        $this->styleSheetPath = $styleSheetPath;
    }

    public function getStylesheetPath()
    {
        if ( ! file_exists($this->styleSheetPath) ) {
            throw new Exceptions\StyleSheetNotFoundException(sprintf("The stylesheet %s could not be found", $path));
        }

        return $this->styleSheetPath;
    }

    public function getRenderingContent()
    {
        return $this->content;
    }

    public function render()
    {
        $xsl = new \DOMDocument;
        $xsl->load($this->getStylesheetPath());

        $xml = $this->getRenderingContent();

        $proc = new \XSLTProcessor;
        $proc->importStyleSheet($xsl); // attach the xsl rules

        $content = $proc->transformToXML($xml);
    }

    public function setRenderingContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
