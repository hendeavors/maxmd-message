<?php

namespace Endeavors\MaxMD\Message\FileSystem;

interface IFilePathTransformer
{
    function transform();

    function getFilePath() : string;

    function getRelativeFilePath();
}
