<?php

namespace Endeavors\MaxMD\Message;
use Endeavors\MaxMD\Message\Contracts\IViewAttachment;

class StandardCda extends MobileCda implements Contracts\IViewAttachment
{
    const STYLE_SHEET_PATH = __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'cda.xsl';
}
