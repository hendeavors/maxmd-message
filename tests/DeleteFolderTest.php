<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;

class DeleteFolderTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();

        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Some.Folder");

        $folder->Make();
    }

    public function testDeletionOfFolder()
    {
        $folder = Folder::create("Some.Folder");

        $response = $folder->Delete()->ToObject();

        $this->assertTrue($response->success);
    }

    public function tearDown()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Some.Folder");

        $folder->Delete();
    }
}
