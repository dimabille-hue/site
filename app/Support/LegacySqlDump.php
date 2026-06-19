<?php

namespace App\Support;

use RuntimeException;

class LegacySqlDump
{
    public function __construct(private readonly string $path)
    {
        if (! is_file($path)) {
            throw new RuntimeException("Legacy SQL dump not found: {$path}");
        }
    }

    /**
     * @return list<string>
     */
    public function tables(): array
    {
        preg_match_all('/CREATE TABLE `([^`]+)`/', $this->contents(), $matches);

        return array_values(array_unique($matches[1] ?? []));
    }

    /**
     * @return list<string>
     */
    public function columns(string $table): array
    {
        $pattern = '/CREATE TABLE `'.preg_quote($table, '/').'` \((.*?)\) ENGINE/s';

        if (! preg_match($pattern, $this->contents(), $match)) {
            return [];
        }

        preg_match_all('/^\s*`([^`]+)`/m', $match[1], $columns);

        return $columns[1] ?? [];
    }

    public function rowCount(string $table): int
    {
        $count = 0;

        foreach ($this->insertPayloads($table) as $payload) {
            $count += count($this->parseTuples($payload));
        }

        return $count;
    }

    /**
     * @return list<array<string, string|null>>
     */
    public function rows(string $table, ?int $limit = null): array
    {
        $columns = $this->columns($table);
        $rows = [];

        foreach ($this->insertPayloads($table) as $payload) {
            foreach ($this->parseTuples($payload) as $tuple) {
                $rows[] = array_combine($columns, array_pad($tuple, count($columns), null));

                if ($limit !== null && count($rows) >= $limit) {
                    return $rows;
                }
            }
        }

        return $rows;
    }

    /**
     * @return array<string, int>
     */
    public function rowCounts(): array
    {
        $counts = [];

        foreach ($this->tables() as $table) {
            $counts[$table] = $this->rowCount($table);
        }

        return $counts;
    }

    /**
     * @return list<string>
     */
    private function insertPayloads(string $table): array
    {
        $pattern = '/INSERT INTO `'.preg_quote($table, '/').'` VALUES (.*?);/s';
        preg_match_all($pattern, $this->contents(), $matches);

        return $matches[1] ?? [];
    }

    /**
     * @return list<list<string|null>>
     */
    private function parseTuples(string $payload): array
    {
        $tuples = [];
        $currentTuple = [];
        $currentValue = '';
        $inString = false;
        $escaping = false;
        $insideTuple = false;
        $length = strlen($payload);

        for ($i = 0; $i < $length; $i++) {
            $char = $payload[$i];

            if ($insideTuple && $inString) {
                if ($escaping) {
                    $currentValue .= match ($char) {
                        'n' => "\n",
                        'r' => "\r",
                        't' => "\t",
                        '0' => "\0",
                        default => $char,
                    };
                    $escaping = false;
                    continue;
                }

                if ($char === '\\') {
                    $escaping = true;
                    continue;
                }

                if ($char === "'") {
                    $inString = false;
                    continue;
                }

                $currentValue .= $char;
                continue;
            }

            if ($char === '(' && ! $insideTuple) {
                $insideTuple = true;
                $currentTuple = [];
                $currentValue = '';
                continue;
            }

            if (! $insideTuple) {
                continue;
            }

            if ($char === "'") {
                $inString = true;
                continue;
            }

            if ($char === ',' || $char === ')') {
                $currentTuple[] = $this->normalizeValue($currentValue);
                $currentValue = '';

                if ($char === ')') {
                    $tuples[] = $currentTuple;
                    $insideTuple = false;
                }

                continue;
            }

            $currentValue .= $char;
        }

        return $tuples;
    }

    private function normalizeValue(string $value): ?string
    {
        $value = trim($value);

        if (strtoupper($value) === 'NULL') {
            return null;
        }

        return $value;
    }

    private function contents(): string
    {
        static $contents = [];

        return $contents[$this->path] ??= file_get_contents($this->path);
    }
}
