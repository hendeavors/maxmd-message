<?php

namespace Endeavors\MaxMD\Message\FileSystem;

class WindowsFilePathTransformer implements IFilePathTransformer
{
    private $filePath;

    private $directory;

    private $relativeFilePath;

    public function __construct(string $filePath, string $directory)
    {
        $this->filePath = $filePath;

        $this->directory = $directory;
    }

    public function transform()
    {
        if(strlen($this->filePath) > 255) {
            $ext = pathinfo($this->filePath, PATHINFO_EXTENSION);
            $this->filePath = substr($this->filePath, 0, 255 - 1 - strlen($ext)) . "." . $ext;
            // todo test
            $this->relativeFilePath = str_replace($this->directory, '', $this->filePath);

            if($this->relativeFilePath[0] !== DIRECTORY_SEPARATOR)
                $this->relativeFilePath = DIRECTORY_SEPARATOR . $this->relativeFilePath;
        }
    }

    public function getFilePath() : string
    {
        return $this->filePath;
    }

    public function getRelativeFilePath()
    {
        return $this->relativeFilePath;
    }
}
