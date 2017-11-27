<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Message;

class MessageFhirQueryTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testSendingFhirQueryWithResourceType()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'resources' => [[
                'resource' => 'Bundle'
            ]],
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])->SendFHIRQuery();

        $this->assertTrue($response->success);
    }

    public function testSendingFhirQueryWithRandomResourceType()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'resources' => [[
                'resource' => 'some random resource type' . rand(0,999)
            ]],
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])->SendFHIRQuery();

        $this->assertTrue($response->success);
    }

    public function testSendingFhirQueryWithResourceTypeAndQueryParameters()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'resources' => [[
                'resource' => 'some random resource type' . rand(0,999)
            ]],
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])->SendFHIRQuery();

        $this->assertTrue($response->success);
    }

    /**
     * @expectedException Endeavors\MaxMD\Message\Exceptions\InvalidFHIRQueryException
     */
    public function testSendingFhirQueryWithoutResourceType()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'sender' => 'freddie@healthendeavors.direct.eval.md',
            'resources' => [],
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])->SendFHIRQuery();

        //$this->assertTrue($response->success);
    }
    
    /**
     * @expectedException Endeavors\MaxMD\Message\Exceptions\InvalidFHIRQueryException
     */
    public function testSendingFhirQueryWithoutResources()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])->SendFHIRQuery();

        //$this->assertTrue($response->success);
    }
    
    /**
     * @expectedException Endeavors\MaxMD\Message\Exceptions\InvalidResourceException
     */
    public function testSendingFhirQueryWithoutResourceParameter()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $response = Message::create([
            'resources' => [[
                'bad' => 'one'
            ]],
            'recipients' => [[
                'email' => 'stevejones1231224@healthendeavors.direct.eval.md',
                'type' => 'TO'
            ]]
        ])->SendFHIRQuery();

        //$this->assertTrue($response->success);
    }

    public function tearDown()
    {        
        parent::tearDown();
    }
}