<?php

namespace App\Support\Version\Parsers;

use App\Support\Version\Exceptions\InvalidVersionFileException;

final class VersionParser
{
    /**
     * @return array<string, string>
     *
     * @throws InvalidVersionFileException
     */
    public function parse(string $path): array
    {
        if (! file_exists($path)) {
            throw new InvalidVersionFileException;
        }

        $data = [];

        foreach (file($path, FILE_IGNORE_NEW_LINES) as $line) {
            $line = trim($line);

            if (
                $line === '' ||
                str_starts_with($line, '#') ||
                ! str_contains($line, '=')
            ) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            $data[$key] = trim($value);
        }

        return $data;
    }
}
