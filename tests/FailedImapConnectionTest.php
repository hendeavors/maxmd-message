<?php
// $mailbox = new PhpImap\Mailbox('{imap.gmail.com:993/imap/ssl}INBOX', 'some@gmail.com', '*********', __DIR__);

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\Attachments;
use Endeavors\MaxMD\Message\Imap\Connection;
use Endeavors\MaxMD\Message\Imap\Mailbox;

class FailedImapConnectionTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        User::logout();
        
        parent::setUp();
    }

    /**
     * @expectedException \Endeavors\MaxMD\Message\Exceptions\Imap\UnauthorizedAccessException
     */
    public function testConnection()
    {
        User::login("sdfdfs", "JW9gzlUJA1VbFd5a6Teax8sdfsdf3wSg");
            
        $conn = Connection::make('Inbox');

        $conn->searchMailbox('ALL');
    }

    public function tearDown()
    {
        User::logout();

        parent::tearDown();
    }
}