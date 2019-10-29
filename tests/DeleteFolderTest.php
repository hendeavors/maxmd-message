<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;

class DeleteFolderTest extends TestCase
{
    public function testDeletionOfFolder()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Some.Folder");

        $response = $folder->Delete()->ToObject();

        $this->assertTrue($response->success);
    }
}
