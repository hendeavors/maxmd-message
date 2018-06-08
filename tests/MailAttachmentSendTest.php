<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Message;

class MailAttachmentSendTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSendingMessage()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])->Send();

        $this->assertTrue($response->success);
    }

    public function testSendingMessageWithTextFile()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])
        ->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . "testfile.txt")
        ->Send();

        $this->assertTrue($response->success);
    }

    public function testSendingMessageWithXmlFile()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])
        ->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . "testfile.xml")
        ->Send();

        $this->assertTrue($response->success);
    }

    public function testSendingMessageWithXmlAndTextFile()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])
        ->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . "testfile.xml")
        ->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . "testfile.txt")
        ->Send();

        $this->assertTrue($response->success);
    }

    public function testSendingMessageWordDocumentFile()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])
        ->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . "maxmdbinarytest.docx")
        ->Send();

        $this->assertTrue($response->success);
    }

    public function testSendingMessagePngFile()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])
        ->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . "somepng.png")
        ->Send();

        $this->assertTrue($response->success);
    }

    public function testSendingMessagePngFileWithCustomFileName()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])
        ->setFileName('Your profile picture')
        ->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . "somepng.png")
        ->Send();

        $this->assertTrue($response->success);
    }

    public function testSendingMessageCustomFileNameWithReallyLongFileName()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])
        ->setFileName('A long file name')
        ->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . "somereallyreallyreallyreallyreallyreallyreallyreallyreallylongfilename.txt")
        ->Send();

        $this->assertTrue($response->success);
    }

    /**
     * @expectedException \Endeavors\MaxMD\Message\Exceptions\FileNotFoundException
     */
    public function testSendingMessageWithBadFilePath()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])
        ->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . "badfile.xml")
        ->addAttachment(__DIR__ . DIRECTORY_SEPARATOR . "badfile.txt")
        ->Send();

        $this->assertTrue($response->success);
    }
}
