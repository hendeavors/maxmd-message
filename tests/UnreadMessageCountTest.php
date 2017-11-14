<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;

class UnreadMessageCountTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCountingMessagesInFolderAfterAll()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Inbox");

        $folder->Messages();

        $this->assertEquals(0, $folder->UnreadMessageCount());
    }
}