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

    public function testPagingMessagesFromInbox()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("Inbox");
        
        // freddie has emails in his inbox

        $items = $folder->Messages()->Paginate();

        $this->assertTrue(is_array($items->paginate()));
    }
    
    /**
     * at the time of this test we had 11 messages
     */
    public function testPagingNavigationMessagesFromInbox()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("Inbox");
        
        // freddie has emails in his inbox

        $items = $folder->Messages()->Paginate(3);

        $this->assertTrue(is_array($items->paginate()));

        $this->assertEquals(3, count($items->paginate()));
        
        // assuming the message never gets deleted
        $itemThree = $items->paginate()[2];

        $this->assertEquals(4, $itemThree->uid);

        $items->next();

        $this->assertEquals(3, count($items->paginate()));

        // assuming the message never gets deleted
        $secondItemThree = $items->paginate()[2];
        // assuming the uid is unique
        $this->assertNotEquals($itemThree->uid, $secondItemThree->uid);
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

    public function testViewingMessagesInfolderWithNoMessages()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("Inbox.Spam");

        $message = $folder->Messages()->All();
    }

    public function testViewingSentFolder()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("Inbox.Sent");

        $messageCount = $folder->Messages()->Count();

        $this->assertTrue($messageCount > 0);
    }

    public function testViewingSentFolderOfLowerCaseArgument()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("inbox.sent");

        $messageCount = $folder->Messages()->Count();

        $this->assertTrue($messageCount > 0);
    }

    public function testViewingSentFolderOfLowerCaseArgumentSingleWord()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");
        
        $folder = Folder::create("dumb");

        $messageCount = $folder->Messages()->Count();

        $folder = Folder::create("sent");
        
        $messageCount = $folder->Messages()->Count();
    }
}