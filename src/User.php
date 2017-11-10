<?php

namespace Endeavors\MaxMD\Message;

use Endeavors\Support\VO\ModernString;

class User implements Contracts\IAuthenticableUser
{
    private static $instance = null;

    protected $username;

    protected $password;

    private function __construct($username, $password)
    {
        $this->setUsername($username);

        $this->setPassword($password);
    }

    final private static function instance()
    {
        return static::$instance;
    }

    final public static function getInstance()
    {
        if(null === static::instance()) {
            throw new Exceptions\InvalidUserException("You must login prior to using the direct messaging api.");
        }

        return static::instance();
    }

    public static function logout()
    {
        static::$instance = null;
    }

    public static function login($username, $password)
    {
        return static::create($username, $password);
    }

    public static function create($username, $password)
    {
        if(null === static::instance()) {
            static::$instance = new User($username, $password);
        }

        return static::instance();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function ToArray()
    {
        return [
            "username" => $this->getUsername(),
            "password" => $this->getPassword()
        ];
    }
    
    /**
     * @throws Exceptions\InvalidUsernameException
     */
    protected function setUsername($username)
    {
        $username = ModernString::create($username);

        if( $username->isEmpty() ) {
            throw new Exceptions\InvalidUsernameException("The username cannot be empty");
        }

        $this->username = $username->get();
    }
    
    /**
     * @throws Exceptions\InvalidUsernameException
     */
    protected function setPassword($password)
    {
        $password = ModernString::create($password);

        if($password->isEmpty()) {
            throw new Exceptions\InvalidPasswordException("The password cannot be empty");
        }

        $this->password = $password->get();
    }
}