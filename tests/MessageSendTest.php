<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Message;

class MessageSendTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSendingMessageToInsideEmail()
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
    
    /**
     * Message::create defaults to loose
     */
    public function testSendingMessageToInvalidEmail()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'move.',
                'type' => 'TO'
            ]]
        ])->Send();

        $this->assertFalse($response->success);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSendingMessageToInvalidEmailInStrictMode()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::strict([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => "true",
            'body' => 'test',
            'recipients' => [[
                'email' => 'move.',
                'type' => 'TO'
            ]]
        ])->Send();

        $this->assertFalse($response->success);
    }
    
    /**
     * Loose mode removes the bad recipients
     */
    public function testSendingMessageToInvalidEmailInLooseMode()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::loose([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => "true",
            'body' => 'test',
            'recipients' => [[
                'email' => 'move.',
                'type' => 'TO'
            ]]
        ])->Send();

        $this->assertFalse($response->success);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testSendingMessageToInvalidEmailAndValidEmailInStrictMode()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::strict([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ],[
                'email' => 'move.',
                'type' => 'TO'
            ]]
        ])->Send();

        $this->assertFalse($response->success);
    }
    
    /**
     * We should have a success response in loose mode with one valid email
     */
    public function testSendingMessageToInvalidEmailAndValidEmailInLooseMode()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::loose([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'htmlBody' => true,
            'body' => 'test',
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ],[
                'email' => 'invalid.',
                'type' => 'TO'
            ]]
        ])->Send();

        $this->assertTrue($response->success);
    }

    public function tearDown()
    {        
        parent::tearDown();
    }
}