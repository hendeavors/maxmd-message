<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;

class UserCreationTest extends TestCase
{
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
