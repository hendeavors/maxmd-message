<?php

namespace Endeavors\MaxMD\Message\Contracts;

interface IBinaryFile
{
    function getContent(): string;

    function getContentType(): string;

    function getFileName(): string;
}
