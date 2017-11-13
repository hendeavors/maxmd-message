<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;

class RenameFolderTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testRenamingOfFolder()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Some.FolderTwo");

        $createResponse = $folder->Make();

        $newFolder = Folder::create('Some.FolderThree');

        $response = $folder->Rename($newFolder)->ToObject();

        $this->assertTrue($response->success);
    }

    public function tearDown()
    {
        $folder = Folder::create("Some.FolderThree");

        $folder->Delete();

        $newFolder = Folder::create("Some.FolderTwo");

        $newFolder->Delete();

        parent::tearDown();
    }
}