<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\MessageDetail;

class MessageDetailReceivedDataTest extends TestCase
{
    public function testTimezoneOffset()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Inbox");

        // assume freddie has emails in his inbox

        $message = $folder->Messages()->View(9);

        $this->assertTrue(is_numeric($message->timeZoneOffset));

        $this->assertNotNull($message->carbonDateTime);

        $this->assertNotNull($message->subject);

        $this->assertNotNull($message->detailDateTime);
    }

    public function testDraftBox()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Inbox.Drafts");

        // assume freddie has emails in his inbox

        $message = $folder->Messages()->View(1);

        $this->assertTrue(is_numeric($message->timeZoneOffset));

        $this->assertNotNull($message->carbonDateTime);

        $this->assertNotNull($message->subject);

        $this->assertNotNull($message->detailDateTime);
    }
}
