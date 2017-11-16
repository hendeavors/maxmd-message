<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\MessageDetail;

class MessageDetailReceivedDataTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testTimezoneOffset()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("Inbox");
        
        // freddie has emails in his inbox

        $message = $folder->Messages()->View(9);

        $this->assertTrue(str_contains($message->receivedAt, "Nov"));

        $this->assertTrue(is_numeric($message->receivedTimeZoneOffset));

        $this->assertNotNull($message->receivedTime);

        $this->assertNotNull($message->receivedDateOrTime);

        $this->assertNotNull($message->sentOrReceivedAt);

        $this->assertNotNull($message->subject);

        $this->assertNotNull($message->sentOrReceivedAtDateTime);

        $this->assertNotNull($message->detailDateTime);
    }

    public function testDraftBox()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("Inbox.Drafts");
        
        // freddie has emails in his inbox

        $message = $folder->Messages()->View(1);

        $this->assertTrue(str_contains($message->receivedAt, "Nov"));

        $this->assertTrue(is_numeric($message->receivedTimeZoneOffset));

        $this->assertNotNull($message->receivedTime);

        $this->assertNotNull($message->receivedDateOrTime);

        $this->assertNotNull($message->sentOrReceivedAt);

        $this->assertNotNull($message->subject);

        $this->assertNotNull($message->sentOrReceivedAtDateTime);
    }
}