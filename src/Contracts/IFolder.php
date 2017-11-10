<?php

namespace Endeavors\MaxMD\Message\Contracts;

interface IFolder
{
    function Make ();
    
    function Rename (IFolder $folder);
    
    function Delete ();

    function get();
}