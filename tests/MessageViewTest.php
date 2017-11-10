<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\MessageDetail;

class MessageViewTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testViewingSingleMessageFromInbox()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("Inbox");
        
        // freddie has emails in his inbox

        $message = $folder->Messages()->View(1);

        $this->assertNotNull($message->sender);

        $this->assertNotNull($message->body);

        $this->assertNotNull($message->subject);

        $this->assertNotNull($message->recipients);

        $this->assertNotNull($message->id);

        $this->assertTrue(is_numeric($message->id));

        $this->assertInstanceOf(MessageDetail::class, $message);
    }

    public function testViewingMessagesFromInbox()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("Inbox");
        
        // freddie has emails in his inbox

        $folder->Messages()->All();
    }
    
    /**
     * confirmed via ui
     */
    public function testMarkingMessageAsRead()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("Inbox");

        $message = $folder->Messages()->View(3);

        $this->assertNotNull($message->sender);
    }

    public function testViewingNonExistingMessage()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("Inbox");

        $message = $folder->Messages()->View(999999);

        $this->assertInstanceOf(MessageDetail::class, $message);

        $this->assertNotNull($message->sender);

        $message = $folder->Messages()->View(-1);
        
        $this->assertInstanceOf(MessageDetail::class, $message);
        
        $this->assertNotNull($message->sender);
    }
}