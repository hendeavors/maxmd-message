<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;

class ViewFoldersTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCreationOfFolder()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Some.Folder" . uniqid());

        $response = $folder->Make()->ToObject();

        $this->assertTrue($response->success);
    }

    public function testCreationOfDuplicateFolder()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Some.Folder");

        $response = $folder->Make()->ToObject();

        $this->assertTrue($response->success);

        $response = $folder->Make()->ToObject();
        
        $this->assertFalse($response->success);
    }
}