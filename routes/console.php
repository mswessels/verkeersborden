<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Sign;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sitemap:generate {--base=}', function () {
    $base = $this->option('base');
    $base = $base ? rtrim($base, '/') : null;

    $sitemapPath = public_path('sitemap.xml');

    $extractLocs = function (string $contents): array {
        if ($contents === '') {
            return [];
        }

        if (!preg_match_all('/<loc>(.*?)<\\/loc>/s', $contents, $matches)) {
            return [];
        }

        $urls = [];
        foreach ($matches[1] as $loc) {
            $loc = trim($loc);
            if ($loc !== '') {
                $urls[] = $loc;
            }
        }

        return $urls;
    };

    $filterSignUrls = function (array $urls): array {
        return array_values(array_filter($urls, function (string $url): bool {
            $path = parse_url($url, PHP_URL_PATH);
            if ($path === null) {
                return true;
            }

            return !preg_match('#^/verkeersborden/[A-Z][0-9]#', $path);
        }));
    };

    $detectBase = function (array $urls): ?string {
        foreach ($urls as $url) {
            $parts = parse_url($url);
            if (!$parts || empty($parts['scheme']) || empty($parts['host'])) {
                continue;
            }

            $base = $parts['scheme'] . '://' . $parts['host'];
            if (!empty($parts['port'])) {
                $base .= ':' . $parts['port'];
            }

            return rtrim($base, '/');
        }

        return null;
    };

    $normalizeToBase = function (array $urls, string $base): array {
        $normalized = [];
        foreach ($urls as $url) {
            $parts = parse_url($url);
            if (!$parts || empty($parts['path'])) {
                $normalized[] = $url;
                continue;
            }

            $rebuilt = $base . $parts['path'];
            if (!empty($parts['query'])) {
                $rebuilt .= '?' . $parts['query'];
            }

            $normalized[] = $rebuilt;
        }

        return $normalized;
    };

    $parseRows = function (string $contents, string $table): array {
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
    };

    $normalizeValue = function (?string $value): ?string {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        if ($value === 'NULL') {
            return null;
        }

        return $value;
    };

    $existingUrls = [];
    if (is_file($sitemapPath)) {
        $contents = file_get_contents($sitemapPath);
        if ($contents !== false) {
            $existingUrls = $extractLocs($contents);
        }
    }

    if (!$base) {
        $base = $detectBase($existingUrls);
    }

    if (!$base) {
        $base = rtrim(config('app.url') ?: 'https://deverkeersborden.nl', '/');
    }

    if ($base === '') {
        $base = 'https://deverkeersborden.nl';
    }

    if (!$existingUrls) {
        $existingUrls = [
            $base . '/',
            $base . '/verkeersborden-oefenen',
            $base . '/alle-verkeersborden',
            $base . '/theorie-examen-oefenen',
            $base . '/quiz',
            $base . '/links',
        ];
    }

    $existingUrls = $normalizeToBase($existingUrls, $base);
    $existingUrls = $filterSignUrls($existingUrls);

    $signUrls = [];
    try {
        if (Schema::hasTable('signs') && Sign::query()->count() > 0) {
            $signs = Sign::query()
                ->select(['code', 'description'])
                ->orderBy('code')
                ->get();

            foreach ($signs as $sign) {
                $source = $sign->description ?: $sign->code;
                $slug = Str::slug($source);
                $signUrls[] = $base . '/verkeersborden/' . $sign->code . '-' . $slug;
            }
        }
    } catch (Throwable $e) {
    }

    $sqlPath = database_path('u50725p66942_verkeer_1766770578.sql');
    if (is_file($sqlPath)) {
        $contents = file_get_contents($sqlPath);
        if ($contents !== false) {
            $rows = $parseRows($contents, 'signs');
            foreach ($rows as $row) {
                if (count($row) < 7) {
                    continue;
                }

                $code = $normalizeValue($row[6]);
                if (!$code) {
                    continue;
                }

                $description = $normalizeValue($row[3]) ?: $code;
                $slug = Str::slug($description);
                $signUrls[] = $base . '/verkeersborden/' . $code . '-' . $slug;
            }
        }
    }

    $unique = [];
    foreach ($existingUrls as $url) {
        $unique[$url] = true;
    }
    foreach ($signUrls as $url) {
        $unique[$url] = true;
    }

    $urls = array_keys($unique);
    $lines = [];
    $lines[] = '<?xml version="1.0" encoding="UTF-8"?>';
    $lines[] = '<urlset';
    $lines[] = '      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
    $lines[] = '      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"';
    $lines[] = '      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9';
    $lines[] = '            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
    $lines[] = '<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->';

    foreach ($urls as $url) {
        $lines[] = '';
        $lines[] = '<url>';
        $lines[] = '  <loc>' . htmlspecialchars($url, ENT_XML1) . '</loc>';
        $lines[] = '  <changefreq>hourly</changefreq>';
        $lines[] = '</url>';
    }

    $lines[] = '</urlset>';

    file_put_contents($sitemapPath, implode(PHP_EOL, $lines) . PHP_EOL);

    $this->info('Sitemap generated with ' . count($urls) . ' URLs.');
})->purpose('Generate sitemap.xml with sign URLs.');
