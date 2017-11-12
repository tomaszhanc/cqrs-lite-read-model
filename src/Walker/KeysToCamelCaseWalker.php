<?php
declare(strict_types=1);

namespace ReadModel\Walker;

class KeysToCamelCaseWalker implements ResultWalker
{
    public function walk(array $result): array
    {
        $camelCased = [];

        array_walk($result, function ($value, $key) use (&$camelCased) {
            $key = is_string($key) ? $this->toCamelCase($key) : $key;

            if (is_array($value)) {
                $camelCased[$key] = $this->walk($value);
            } else {
                $camelCased[$key] = $value;
            }
        }, array_keys($result));

        return $camelCased;
    }

    private function toCamelCase(string $value): string
    {
        $camelCasedName = preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '').strtoupper($match[2]);
        }, $value);

        return lcfirst($camelCasedName);
    }
}
