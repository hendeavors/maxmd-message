<?php

namespace Endeavors\MaxMD\Message\Contracts;

interface IAttachments
{
    function view($id);

    function download($id);
}