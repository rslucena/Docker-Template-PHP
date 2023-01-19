<?php

namespace App\Bootstrap;

use InvalidArgumentException;
use RuntimeException;

class DotEnvBootstrap
{

    static function load(string $Dir): void
    {
        $Dir = realpath($Dir . "/../.env");

        self::validateDirectory($Dir);

        $Lines = file($Dir, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($Lines ?? [] as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            $variable = explode('=', $line, 2);

            $name = trim($variable[0] ?? "");
            $value = trim($variable[1] ?? "");

            if (empty($name) || empty($value)) {
                continue;
            }

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
            }
        }
    }

    /**
     * @param string $Dir
     */
    public static function definePath(string $Dir): void
    {
        self::validateDirectory($Dir);

        $Directories = glob($Dir . '/*', GLOB_ONLYDIR);

        foreach ($Directories ?? [] as $directory) {
            $Path = explode('/', $directory);
            $NameDir = strtoupper('DIR_' . end($Path));
            $Path = $directory;
            putenv(sprintf('%s=%s', $NameDir, $Path));
        }
    }

    /**
     * @param string $Dir
     */
    private static function validateDirectory(string $Dir): void
    {
        if (!file_exists($Dir)) {
            throw new InvalidArgumentException(sprintf('%s does not exist', $Dir));
        }

        if (!is_readable($Dir)) {
            throw new RuntimeException(sprintf('%s file is not readable', $Dir));
        }
    }

}