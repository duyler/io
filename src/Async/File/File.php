<?php

declare(strict_types=1);

namespace Duyler\IO\Async\File;

final class File
{
    public static function read(string $path): FileReader
    {
        $fileReader = new FileReader();
        $fileReader->setPath($path);
        return $fileReader;
    }

    public static function write(string $path, string $contents): FileWriter
    {
        $fileWriter = new FileWriter();
        $fileWriter->setPath($path);
        $fileWriter->setContents($contents);
        return $fileWriter;
    }
}
