<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\XslAttachment;
use Endeavors\MaxMD\Message\XmlAttachment;
use Endeavors\MaxMD\Message\PlainAttachment;

class XslAttachmentCreationTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCreatingXslAttachmentFile()
    {
        $xslAttachment = new XslAttachment(new XmlAttachment(new PlainAttachment(new Attachment($response))));
    }
}
