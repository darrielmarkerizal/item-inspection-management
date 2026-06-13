<?php

namespace Modules\Core\Support;

use RuntimeException;

class CsvImporter
{
    /**
     * Read a CSV file and return each data row as an associative array
     * keyed by the header row. Empty cells become null.
     *
     * @return array<int, array<string, string|null>>
     */
    public static function read(string $path): array
    {
        if (! is_readable($path)) {
            throw new RuntimeException("CSV file not readable: {$path}");
        }

        $handle = fopen($path, 'r');
        $header = null;
        $rows = [];

        while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            if ($header === null) {
                $header = array_map('trim', $row);

                continue;
            }

            if (count(array_filter($row, fn ($value) => $value !== null && $value !== '')) === 0) {
                continue;
            }

            $values = array_map(
                fn ($value) => ($value === null || $value === '') ? null : trim((string) $value),
                $row
            );

            $rows[] = array_combine($header, $values);
        }

        fclose($handle);

        return $rows;
    }
}
