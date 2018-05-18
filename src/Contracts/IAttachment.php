<?php

namespace Endeavors\MaxMD\Message\Contracts;

interface IAttachment
{
    function filename();

    function contentType();

    function content();
}
