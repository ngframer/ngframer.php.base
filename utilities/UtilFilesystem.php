<?php

namespace NGFramer\NGFramerPHPBase\utilities;

final class UtilFilesystem
{
    public static function readDirectory(string $path): array
    {
        $files = scandir($path);
        $files = array_diff($files, ['.', '..']);
        return $files;
    }


    public static function readFile(string $path): string
    {
        return file_get_contents($path);
    }
}