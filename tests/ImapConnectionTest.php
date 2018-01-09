<?php
// $mailbox = new PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}INBOX', 'some@gmail.com', '*********', __DIR__);

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\Attachments;
use Endeavors\MaxMD\Message\Imap\Connection;

class ImapConnectionTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testMailConnection()
    {
        User::login("bryanp1231@healthendeavors.direct.eval.md", "JW9gzj3MlUJA1VbFdi5a6Teax83wSg");
            
        Connection::make();
    }

    public function testInboxMailConnection()
    {
        User::login("bryanp1231@healthendeavors.direct.eval.md", "JW9gzj3MlUJA1VbFdi5a6Teax83wSg");
        
        Connection::make('Inbox');
    }

    public function testSentMailConnection()
    {
        User::login("bryanp1231@healthendeavors.direct.eval.md", "JW9gzj3MlUJA1VbFdi5a6Teax83wSg");
        
        Connection::make('Inbox.Sent');
    }

    public function testAttachmentConnection()
    {
        User::login("bryanp1231@healthendeavors.direct.eval.md", "JW9gzj3MlUJA1VbFdi5a6Teax83wSg");
        
        $conn = Connection::make('Inbox', __DIR__);

        $this->assertInstanceOf(\PhpImap\Mailbox::class, $conn);
    }

    public function tearDown()
    {
        User::logout();

        parent::tearDown();
    }
}