<?php

namespace Endeavors\MaxMD\Message\Contracts;

interface IMessages
{
    /**
     * All the messages
     * @return array
     */
    function All();
    
    /**
     * @return IMessageDetail
     */
    function View($id);
}