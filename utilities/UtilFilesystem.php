<?php

namespace NGFramer\NGFramerPHPBase\utilities;

final class UtilFilesystem
{
    /**
     * Private constructor to prevent instantiation.
     */
    private function __construct()
    {
    }


    /**
     * Read a directory and return its content.
     * @param string $path
     * @return array
     */
    public static function readDirectory(string $path): array
    {
        $files = scandir($path);
        return array_diff($files, ['.', '..']);
    }


    /**
     * Read a file and return its content.
     * @param string $path
     * @return string
     */
    public static function readFile(string $path): string
    {
        return file_get_contents($path);
    }
}