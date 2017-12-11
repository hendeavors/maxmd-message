<?php

namespace Endeavors\MaxMD\Message\Contracts;

interface IAttachment
{
    function view();

    function display($path = null);
}