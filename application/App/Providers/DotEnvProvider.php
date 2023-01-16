<?php

namespace Root\Application\Providers;

use InvalidArgumentException;
use RuntimeException;

class DotEnvProvider
{

    public function __construct(string $Path)
    {
        $Path .= '/.env';

        if (!file_exists($Path)) {
            throw new InvalidArgumentException(sprintf('%s does not exist', $Path));
        }

        if (!is_readable($Path)) {
            throw new RuntimeException(sprintf('%s file is not readable', $Path));
        }

        $Lines = file($Path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

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

}