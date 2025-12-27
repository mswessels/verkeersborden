<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SignSeeder extends Seeder
{
    public function run()
    {
        if (!Schema::hasTable('signs') || !Schema::hasTable('signs_categories')) {
            return;
        }

        $path = database_path('u50725p66942_verkeer_1766770578.sql');
        if (!is_file($path)) {
            return;
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            return;
        }

        $categoryRows = $this->extractRows($contents, 'signs_categories');
        $payload = [];

        foreach ($categoryRows as $row) {
            if (count($row) < 3) {
                continue;
            }

            $payload[] = [
                'id' => (int) $row[0],
                'name' => $this->normalizeValue($row[1]) ?? '',
                'letter' => $this->normalizeValue($row[2]) ?? '',
            ];
        }

        if ($payload) {
            DB::table('signs_categories')->upsert(
                $payload,
                ['id'],
                ['name', 'letter']
            );
        }

        $signRows = $this->extractRows($contents, 'signs');
        $payload = [];

        foreach ($signRows as $row) {
            if (count($row) < 7) {
                continue;
            }

            $payload[] = [
                'id' => (int) $row[0],
                'category_id' => (int) $row[1],
                'name' => $this->normalizeValue($row[2]) ?? '',
                'description' => $this->normalizeValue($row[3]) ?? '',
                'description_short' => $this->normalizeValue($row[4]) ?? '',
                'image' => $this->normalizeValue($row[5]) ?? '',
                'code' => $this->normalizeValue($row[6]) ?? '',
            ];
        }

        if ($payload) {
            foreach (array_chunk($payload, 100) as $chunk) {
                DB::table('signs')->upsert(
                    $chunk,
                    ['id'],
                    ['category_id', 'name', 'description', 'description_short', 'image', 'code']
                );
            }
        }
    }

    private function extractRows(string $contents, string $table): array
    {
        $needle = 'INSERT INTO `' . $table . '` VALUES ';
        $start = strpos($contents, $needle);
        if ($start === false) {
            return [];
        }

        $start += strlen($needle);
        $length = strlen($contents);
        $inQuote = false;
        $escape = false;
        $end = null;

        for ($i = $start; $i < $length; $i++) {
            $char = $contents[$i];
            if ($escape) {
                $escape = false;
                continue;
            }

            if ($char === "\\") {
                $escape = true;
                continue;
            }

            if ($char === "'") {
                $inQuote = !$inQuote;
                continue;
            }

            if (!$inQuote && $char === ';') {
                $end = $i;
                break;
            }
        }

        if ($end === null) {
            return [];
        }

        $values = substr($contents, $start, $end - $start);
        $values = trim($values);
        $values = trim($values, "\r\n\t ");

        if (strpos($values, '(') === 0) {
            $values = substr($values, 1);
        }

        if (substr($values, -1) === ')') {
            $values = substr($values, 0, -1);
        }

        $rows = preg_split('/\\),\\s*\\(/', $values) ?: [];
        $parsed = [];

        foreach ($rows as $row) {
            $row = trim($row);
            if ($row === '') {
                continue;
            }

            $parsed[] = str_getcsv($row, ',', "'", "\\");
        }

        return $parsed;
    }

    private function normalizeValue(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        if ($value === 'NULL') {
            return null;
        }

        return $value;
    }
}
