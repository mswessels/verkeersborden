<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Str;
use App\Sign;
use App\SignCategory;
use App\Services\CbrRijschoolCrawler;

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

Artisan::command('cbr:crawl {--letters=} {--limit=} {--dry-run} {--sleep=200}', function () {
    $lettersOption = $this->option('letters');
    $letters = [];

    if (!$lettersOption || strtolower($lettersOption) === 'all') {
        $letters = range('a', 'z');
    } else {
        foreach (explode(',', $lettersOption) as $chunk) {
            $chunk = strtolower(trim($chunk));
            if ($chunk === '') {
                continue;
            }

            if (preg_match('/^[a-z]-[a-z]$/', $chunk)) {
                $start = ord($chunk[0]);
                $end = ord($chunk[2]);
                if ($start <= $end) {
                    for ($i = $start; $i <= $end; $i++) {
                        $letters[] = chr($i);
                    }
                } else {
                    for ($i = $start; $i >= $end; $i--) {
                        $letters[] = chr($i);
                    }
                }
                continue;
            }

            $letters[] = $chunk;
        }
    }

    $letters = array_values(array_unique($letters));

    if (!$letters) {
        $this->error('Geen letters opgegeven om te crawlen.');
        return;
    }

    $limit = $this->option('limit');
    $limit = $limit !== null ? (int) $limit : null;
    $sleepMs = (int) $this->option('sleep');
    $dryRun = (bool) $this->option('dry-run');

    /** @var CbrRijschoolCrawler $crawler */
    $crawler = app(CbrRijschoolCrawler::class);
    $stats = $crawler->crawl($letters, $dryRun, $limit, $sleepMs, function (string $message): void {
        $this->line($message);
    });

    $this->info('Klaar.');
    $this->line('Queries: ' . $stats['queries']);
    $this->line('Gevonden: ' . $stats['schools_seen']);
    $this->line('Opgeslagen: ' . $stats['schools_saved']);
    $this->line('Overgeslagen: ' . $stats['schools_skipped']);
    $this->line('Fouten: ' . $stats['errors']);
})->purpose('Crawl CBR rijschool data');

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

    $sqlContents = null;
    $sqlPath = database_path('u50725p66942_verkeer_1766770578.sql');
    if (is_file($sqlPath)) {
        $contents = file_get_contents($sqlPath);
        if ($contents !== false) {
            $sqlContents = $contents;
        }
    }

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

    if ($sqlContents) {
        $rows = $parseRows($sqlContents, 'signs');
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

    $seriesUrls = [];
    try {
        if (Schema::hasTable('signs_categories') && SignCategory::query()->count() > 0) {
            $letters = SignCategory::query()->orderBy('letter')->pluck('letter');
            foreach ($letters as $letter) {
                $seriesUrls[] = $base . '/verkeersborden/serie-' . Str::lower($letter);
            }
        }
    } catch (Throwable $e) {
    }

    if ($sqlContents) {
        $rows = $parseRows($sqlContents, 'signs_categories');
        foreach ($rows as $row) {
            if (count($row) < 3) {
                continue;
            }

            $letter = $normalizeValue($row[2]);
            if (!$letter) {
                continue;
            }

            $seriesUrls[] = $base . '/verkeersborden/serie-' . Str::lower($letter);
        }
    }

    $unique = [];
    foreach ($existingUrls as $url) {
        $unique[$url] = true;
    }
    foreach ($signUrls as $url) {
        $unique[$url] = true;
    }
    foreach ($seriesUrls as $url) {
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

Schedule::command('sitemap:generate')
    ->dailyAt('02:15')
    ->withoutOverlapping()
    ->description('Generate sitemap.xml nightly.');

Schedule::command('cbr:crawl --letters=all --sleep=300')
    ->dailyAt('03:00')
    ->withoutOverlapping()
    ->description('Crawl CBR rijschool data nightly.');

Artisan::command('sign-images:generate {--force}', function () {
    $sourceDir = public_path('img/borden');
    if (!is_dir($sourceDir)) {
        $this->error('Source directory not found: ' . $sourceDir);
        return;
    }

    $supportsWebp = function_exists('imagewebp');
    $supportsAvif = function_exists('imageavif');

    if (!$supportsWebp && !$supportsAvif) {
        $this->error('No WebP or AVIF support found in GD.');
        return;
    }

    $files = glob($sourceDir . '/*.{png,jpg,jpeg,JPG,JPEG,PNG}', GLOB_BRACE) ?: [];
    $force = (bool) $this->option('force');
    $webpCount = 0;
    $avifCount = 0;
    $skipped = 0;

    foreach ($files as $file) {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $baseName = pathinfo($file, PATHINFO_FILENAME);
        $webpPath = $sourceDir . '/' . $baseName . '.webp';
        $avifPath = $sourceDir . '/' . $baseName . '.avif';

        if (!$force) {
            $hasWebp = is_file($webpPath);
            $hasAvif = is_file($avifPath);

            if (($hasWebp || !$supportsWebp) && ($hasAvif || !$supportsAvif)) {
                $skipped++;
                continue;
            }
        }

        switch ($extension) {
            case 'png':
                $image = imagecreatefrompng($file);
                if ($image) {
                    imagealphablending($image, true);
                    imagesavealpha($image, true);
                }
                break;
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($file);
                break;
            default:
                $image = false;
        }

        if (!$image) {
            $skipped++;
            continue;
        }

        if ($supportsWebp && (!is_file($webpPath) || $force)) {
            if (imagewebp($image, $webpPath, 82)) {
                $webpCount++;
            }
        }

        if ($supportsAvif && (!is_file($avifPath) || $force)) {
            if (imageavif($image, $avifPath, 60)) {
                $avifCount++;
            }
        }

        imagedestroy($image);
    }

    $this->info('WebP generated: ' . $webpCount);
    $this->info('AVIF generated: ' . $avifCount);
    $this->info('Skipped: ' . $skipped);
})->purpose('Generate WebP/AVIF versions of sign images.');
