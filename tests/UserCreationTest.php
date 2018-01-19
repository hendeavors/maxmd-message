<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;

class UserCreationTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testValidUserCreation()
    {
        $user = User::login("username", "password");
        // should not throw exception
        $user = User::getInstance();

        $this->assertNotNull($user->getUsername());
    }

    /**
     * @expectedException \Endeavors\MaxMD\Message\Exceptions\InvalidUsernameException
     * @expectedExceptionMessage The username cannot be empty
     */
    public function testInvalidUserCreation()
    {
        $user = User::login("", "");
    }

    /**
     * @expectedException \Endeavors\MaxMD\Message\Exceptions\InvalidPasswordException
     * @expectedExceptionMessage The password cannot be empty
     */
    public function testUserCreationWithMissingPassword()
    {
        $user = User::login("username", "");
    }

    public function testSingleLogin()
    {
        User::login("same", "user");

        $this->assertEquals(User::getInstance()->getUsername(), "same");

        User::login("different", "another");

        $this->assertEquals(User::getInstance()->getUsername(), "same");
    }

    public function testLoginThenFreshLogin()
    {
        User::login("same", "user");

        $this->assertEquals(User::getInstance()->getUsername(), "same");

        User::freshLogin("different", "user");

        $this->assertEquals(User::getInstance()->getUsername(), "different");
    }

    public function testFreshLogin()
    {
        User::freshLogin("same", "user");
        
        $this->assertEquals(User::getInstance()->getUsername(), "same");
        
        User::freshLogin("different", "user");
        
        $this->assertEquals(User::getInstance()->getUsername(), "different");
    }
    
    /**
     * @expectedException \Endeavors\MaxMD\Message\Exceptions\InvalidUserException
     * @expectedExceptionMessage You must login prior to using the direct messaging api.
     */
    public function testInvalidUser()
    {
        User::getInstance();
    }

    public function tearDown()
    {
        User::logout();

        parent::tearDown();
    }
}