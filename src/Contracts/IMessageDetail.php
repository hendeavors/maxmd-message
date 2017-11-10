<?php

namespace Endeavors\MaxMD\Message\Contracts;

interface IMessageDetail
{
    /**
     * The id of the message
     */
    function id();

    /**
     * The subject of the message
     */
    function subject();
    
    /**
     * The sender (from) of the message
     */
    function sender();
    
    /**
     * The body (contents) of the message
     */
    function body();
}