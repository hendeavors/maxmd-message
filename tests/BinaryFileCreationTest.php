<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\BinaryFile;

class BinaryFileCreationTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testCreatingBinaryFile()
    {
        $binaryFile = new BinaryFile();

        $binaryFile->getFileName();
    }
}
