<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;

class ChildFolderTest extends TestCase
{
    public function testGettingChildrenOfFolder()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Some");

        $response = $folder->Children()->ToObject();

        $this->assertTrue($response->success);
    }
}
