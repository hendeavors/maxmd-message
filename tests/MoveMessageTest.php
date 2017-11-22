<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;

class MoveMessageTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        // we are marking this skipped. when the message gets moved the test is over
        // todo create routing to reset the move
        $this->markTestSkipped();

        parent::setUp();
    }

    public function testMovingFromInboxToTrash()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Inbox");

        $tofolder = Folder::create("Trash");

        $response = $folder->MoveMessages([36], $tofolder)->ToObject();

        $this->assertTrue($response->success);
    }

    public function testMovingFromInboxToTrashWithPrefix()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Inbox");

        $tofolder = Folder::create("Inbox.Trash");

        $response = $folder->MoveMessages([11], $tofolder)->ToObject();

        $this->assertTrue($response->success);
    }

    public function testMovingFromTrashToInbox()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Trash");

        $tofolder = Folder::create("Inbox");

        $response = $folder->MoveMessages([9], $tofolder)->ToObject();

        $this->assertTrue($response->success);
    }

    public function testMovingFromTrashToInboxWithPrefix()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Inbox.Trash");

        $tofolder = Folder::create("Inbox");

        $response = $folder->MoveMessages([10], $tofolder)->ToObject();

        $this->assertTrue($response->success);
    }

    public function tearDown()
    {        
        parent::tearDown();
    }
}