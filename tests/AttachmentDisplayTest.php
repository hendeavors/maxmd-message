<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\MessageDetail;
use Endeavors\MaxMD\Message\Attachments;

class AttachmentDisplayTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    public function testGettingValidCCDAttachments()
    {
        User::login("stevejones1231224@healthendeavors.direct.eval.md", "zXV6nPipZXC4wYY89veQXmHG9YvBkX");
        
        $folder = Folder::create("Inbox");

        $message = $folder->Messages()->View(503);

        $attachments = $message->attachments();

        foreach($attachments->get() as $attachment) {
            //$this->assertTrue($attachment->display());
        }

        $this->assertInstanceOf(Attachments::class, $attachments);
    }

    public function testGettingValidCCDAttachmentsUsingAlias()
    {
        User::login("stevejones1231224@healthendeavors.direct.eval.md", "zXV6nPipZXC4wYY89veQXmHG9YvBkX");
        
        $folder = Folder::create("Inbox");

        $message = $folder->Messages()->View(503);

        $attachments = $message->attachments();

        foreach($attachments->get() as $attachment) {
            //$this->assertTrue($attachment->view());
        }

        $this->assertInstanceOf(Attachments::class, $attachments);
    }

    public function testGettingInValidCCDAttachments()
    {
        User::login("stevejones1231224@healthendeavors.direct.eval.md", "zXV6nPipZXC4wYY89veQXmHG9YvBkX");
        
        $folder = Folder::create("Inbox");

        $message = $folder->Messages()->View(506);

        $attachments = $message->attachments();

        foreach($attachments->get() as $attachment) {
            //$this->assertFalse($attachment->display());
        }

        $this->assertInstanceOf(Attachments::class, $attachments);
    }

    public function testGettingInValidCCDAttachmentsUsingAlias()
    {
        User::login("stevejones1231224@healthendeavors.direct.eval.md", "zXV6nPipZXC4wYY89veQXmHG9YvBkX");
        
        $folder = Folder::create("Inbox");

        $message = $folder->Messages()->View(506);

        $attachments = $message->attachments();

        foreach($attachments->get() as $attachment) {
            //$this->assertFalse($attachment->view());
        }

        $this->assertInstanceOf(Attachments::class, $attachments);
    }

    public function tearDown()
    {
        User::logout();

        parent::tearDown();
    }
}