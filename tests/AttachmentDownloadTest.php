<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\MessageDetail;
use Endeavors\MaxMD\Message\Attachments;

class AttachmentDownloadTest extends TestCase
{
    public function testGettingAttachments()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Inbox");

        $message = $folder->Messages()->View(13);

        $attachments = $message->attachments();

        $this->assertInstanceOf(Attachments::class, $attachments);
    }

    public function testGettingAttachmentWithoutFilename()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("inbox.sent");

        $message = $folder->Messages()->View(7);

        $attachments = $message->attachments();

        $this->assertInstanceOf(Attachments::class, $attachments);

        foreach($attachments->get() as $attachment) {
            $this->assertNull($attachment->filename());
        }
    }

    public function testGettingAttachmentsWithoutFilename()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Inbox");

        $message = $folder->Messages()->View(14);

        $attachments = $message->attachments();

        $this->assertInstanceOf(Attachments::class, $attachments);

        foreach($attachments as $attachment) {
            $this->assertNull($attachment->filename());
        }
    }

    public function testGettingAttachmentsOfNonExistentMessage()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Inbox");

        $message = $folder->Messages()->View(999999);

        $attachments = $message->attachments();

        $this->assertInstanceOf(Attachments::class, $attachments);

        foreach($attachments as $attachment) {
            $this->assertNull($attachment->filename());
        }
    }
}
