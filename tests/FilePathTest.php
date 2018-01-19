<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\Attachments;

class FilePathTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testImapAttachmentFilePath()
    {
        User::login("bryanp1231@healthendeavors.direct.eval.md", "JW9gzj3MlUJA1VbFdi5a6Teax83wSg");
            
        $folder = Folder::create("inbox");
    
        $attachments = $folder->attachments();
    
        foreach($attachments->get() as $attachment) {
            $this->assertNotNull($attachment['attachment']->filePath());
        }
    }

    public function testImapAttachmentRelativeFilePath()
    {
        User::login("bryanp1231@healthendeavors.direct.eval.md", "JW9gzj3MlUJA1VbFdi5a6Teax83wSg");
        
        $folder = Folder::create("inbox");

        $attachments = $folder->attachments();

        foreach($attachments->get() as $attachment) {
            $this->assertNotNull($attachment['attachment']->relativeFilePath());
        }
    }

    public function tearDown()
    {
        User::logout();

        parent::tearDown();
    }
}