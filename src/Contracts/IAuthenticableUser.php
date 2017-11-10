<?php

namespace Endeavors\MaxMD\Message\Contracts;

interface IAuthenticableUser
{
    function getUsername();

    function getPassword();
    
    function ToArray();
}