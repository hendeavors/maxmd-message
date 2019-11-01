<?php
// $mailbox = new PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}INBOX', 'some@gmail.com', '*********', __DIR__);

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\Attachments;
use Endeavors\MaxMD\Message\Imap\Connection;

class FolderNameTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testFolderNameIsOfCorrectFormat()
    {
        $folder = Folder::create('inbox.sent');

        $this->assertEquals($folder->get(), 'Inbox.Sent');

        $folder = Folder::create('Inbox.sent');
        
        $this->assertEquals($folder->get(), 'Inbox.Sent');

        $folder = Folder::create('inbox');
        
        $this->assertEquals($folder->get(), 'Inbox');
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}