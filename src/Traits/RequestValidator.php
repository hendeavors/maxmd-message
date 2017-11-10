<?php

namespace Endeavors\MaxMD\Message\Traits;

use Endeavors\MaxMD\Message\Exceptions;

trait RequestValidator {

    public function validateReservedFolders()
    {
        if( $this->reserved()->inArray($this->get()) ) {
            throw new Exceptions\ReservedFolderException($this->reserved()->implode() .  " are reserved folder(s).");
        }
    }
}