<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\MessageDetail;

class MessageRetrievalPerformanceTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testViewingSingleMessageFromInbox()
    {
        User::login("stevejones1231224@healthendeavors.direct.eval.md", "zXV6nPipZXC4wYY89veQXmHG9YvBkX");
        
        $folder = Folder::create("Inbox");

        $message = $folder->Messages();
    }
}