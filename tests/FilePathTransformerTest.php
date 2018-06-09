<?php

namespace Endeavors\MaxMD\Message\Tests;

use Endeavors\MaxMD\Message\User;
use Endeavors\MaxMD\Message\Folder;
use Endeavors\MaxMD\Message\Attachments;

class FilePathTransformerTest extends \Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testTransformingShortFilePath()
    {
        $filePathTransformer = new \Endeavors\MaxMD\Message\FileSystem\WindowsFilePathTransformer("path", __DIR__);
    }

    public function testTransformingLongFilePath()
    {
        $filePathTransformer = new \Endeavors\MaxMD\Message\FileSystem\WindowsFilePathTransformer(__DIR__ . "\A512345\99_53641b7e3d91b9c397071fd82cf7b80c55ee466c_146_79401e54887d4a056af398a0c83ffc72f738432_124_f971e4ef91a50fb140fd51bf1dca865d1c187456_1_6026d00842cb1bcbb66d6d7cafb6907cbd10875c_CCDA_R21_CCD.xml", __DIR__);
        $filePathTransformer->transform();
        // we want a directory separator, we'll only get a relativepath if the filepath is > 255
        $directorySeparators = substr($filePathTransformer->getRelativeFilePath(), 0, 2);

        $this->assertEquals($directorySeparators[0], '\\');
        $this->assertNotEquals($directorySeparators[1], '\\');
    }

    public function testTransformingLongFilePathNoSeparator()
    {
        $filePathTransformer = new \Endeavors\MaxMD\Message\FileSystem\WindowsFilePathTransformer(__DIR__ . "A512345\99_53641b7e3d91b9c397071fd82cf7b80c55ee466c_146_79401e54887d4a056af398a0c83ffc72f738432_124_f971e4ef91a50fb140fd51bf1dca865d1c187456_1_6026d00842cb1bcbb66d6d7cafb6907cbd10875c_CCDA_R21_CCD.xml", __DIR__);
        $filePathTransformer->transform();
        // we want a directory separator, we'll only get a relativepath if the filepath is > 255
        $directorySeparators = substr($filePathTransformer->getRelativeFilePath(), 0, 2);

        $this->assertEquals($directorySeparators[0], '\\');
        $this->assertNotEquals($directorySeparators[1], '\\');
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
