<?php
// $mailbox = new PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}INBOX', 'some@gmail.com', '*********', __DIR__);

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\Attachments;

class PrototypeImapTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->markTestSkipped();
    }

    public function testImapAttachmentsFromMessageDetail()
    {

        User::login("bryanp1231@healthendeavors.direct.eval.md", "JW9gzj3MlUJA1VbFdi5a6Teax83wSg");
            
        $folder = Folder::create("inbox");
    
        $attachments = $folder->attachments();
    
        foreach($attachments->get() as $attachment) {
            $this->assertNotNull($attachment['attachment']->view());
        }

        $attachments = $folder->imapAttachments();
    
        foreach($attachments->get() as $attachment) {
            $this->assertNotNull($attachment['attachment']->view());
        }
    
        $this->assertInstanceOf(Attachments::class, $attachments);
    }

    public function tearDown()
    {
        User::logout();

        parent::tearDown();
    }
}