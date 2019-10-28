<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;

class CreateFolderExceptionTest extends TestCase
{

    /**
     * @expectedException \Endeavors\MaxMD\Message\Exceptions\ReservedFolderException
     */
    public function testCreationOfReservedFolder()
    {
        User::login("freddie@healthendeavors.direct.eval.md", "smith");

        $folder = Folder::create("Spam");

        $folder->Make();
    }
}
