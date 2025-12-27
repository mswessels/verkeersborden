<?php namespace App\Services;

use App\Rijschool;
use DOMDocument;
use DOMNode;
use DOMXPath;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class CbrRijschoolCrawler
{
    private const BASE_URL = 'https://www.cbr.nl';
    private const SEARCH_PATH = '/nl/rijschoolzoeker/rijschool-zoeken-op-naam';

    public function crawl(array $letters, bool $dryRun = false, ?int $limit = null, int $sleepMs = 0, ?callable $progress = null): array
    {
        $stats = [
            'queries' => 0,
            'schools_seen' => 0,
            'schools_saved' => 0,
            'schools_skipped' => 0,
            'errors' => 0,
        ];

        $seen = [];

        foreach ($letters as $letter) {
            $letter = trim($letter);
            if ($letter === '') {
                continue;
            }

            $stats['queries']++;
            $this->log($progress, "Zoekterm: {$letter}");

            try {
                $searchHtml = $this->fetch(self::SEARCH_PATH . '?query=' . urlencode($letter));
            } catch (RuntimeException $e) {
                $stats['errors']++;
                $this->log($progress, "Fout bij ophalen zoekpagina ({$letter}): " . $e->getMessage());
                continue;
            }

            $searchUrl = $this->extractSearchUrl($searchHtml);
            if ($searchUrl === null) {
                $stats['errors']++;
                $this->log($progress, "Geen search URL gevonden voor zoekterm {$letter}.");
                continue;
            }

            $offset = 0;
            $firstPageHtml = null;
            $totalCount = null;
            $pageSize = null;

            while (true) {
                $resultsHtml = $offset === 0
                    ? ($firstPageHtml ??= $this->fetchSearchResultsHtml($searchUrl, 0))
                    : $this->fetchSearchResultsHtml($searchUrl, $offset);

                if ($totalCount === null) {
                    $totalCount = $this->extractResultCount($resultsHtml);
                }

                $links = $this->extractSchoolLinks($resultsHtml);
                $pageSize ??= count($links);

                if ($pageSize === 0) {
                    break;
                }

                foreach ($links as $link) {
                    if (isset($seen[$link])) {
                        $stats['schools_skipped']++;
                        continue;
                    }

                    $seen[$link] = true;
                    $stats['schools_seen']++;

                    try {
                        $detailHtml = $this->fetch($link);
                        $details = $this->parseSchoolDetails($detailHtml);
                        $this->persistSchool($link, $details, $dryRun);
                        $stats['schools_saved']++;
                    } catch (RuntimeException $e) {
                        $stats['errors']++;
                        $this->log($progress, "Fout bij ophalen school ({$link}): " . $e->getMessage());
                    }

                    if ($limit !== null && $stats['schools_saved'] >= $limit) {
                        return $stats;
                    }

                    $this->sleep($sleepMs);
                }

                $offset += $pageSize;
                if ($totalCount !== null && $offset >= $totalCount) {
                    break;
                }
            }
        }

        return $stats;
    }

    public function extractSearchUrl(string $html): ?string
    {
        $xpath = $this->makeXPath($html);
        $node = $xpath->query("//*[@id='searchresult' and @data-searchurl]")->item(0);
        if (!$node instanceof DOMNode) {
            $node = $xpath->query("//*[@data-searchurl]")->item(0);
        }

        if (!$node instanceof DOMNode) {
            return null;
        }

        $searchUrl = html_entity_decode($node->attributes->getNamedItem('data-searchurl')->nodeValue ?? '', ENT_QUOTES);
        if ($searchUrl === '') {
            return null;
        }

        return $this->toAbsoluteUrl($searchUrl);
    }

    public function extractResultCount(string $html): ?int
    {
        $xpath = $this->makeXPath($html);
        $text = $this->cleanText($xpath->evaluate("string(//div[contains(@class,'search-result-count')])"));
        if ($text === '') {
            return null;
        }

        if (preg_match('/(\\d+)/', $text, $match)) {
            return (int) $match[1];
        }

        return null;
    }

    public function extractSchoolLinks(string $html): array
    {
        $xpath = $this->makeXPath($html);
        $nodes = $xpath->query("//div[@id='searchresultbox']//a[@href]");

        $links = [];
        $seen = [];

        foreach ($nodes as $node) {
            $href = $node->attributes->getNamedItem('href')->nodeValue ?? '';
            if ($href === '' || !str_contains($href, '/rijscholen/')) {
                continue;
            }

            $url = $this->toAbsoluteUrl($href);
            if (isset($seen[$url])) {
                continue;
            }

            $seen[$url] = true;
            $links[] = $url;
        }

        return $links;
    }

    public function parseSchoolDetails(string $html): array
    {
        $xpath = $this->makeXPath($html);
        $details = [
            'name' => $this->cleanText($xpath->evaluate("string(//h1//span)")),
            'address_raw' => '',
            'street' => '',
            'postal_code' => '',
            'city' => '',
            'phone' => '',
            'email' => '',
            'website' => '',
            'praktijkopleidingen' => [],
            'theorieopleidingen' => [],
            'beroepsopleidingen' => [],
            'bijzonderheden' => [],
            'rijschoolnummer' => '',
            'kvk_nummer' => '',
            'exam_results' => [],
            'coordinates' => [],
            'cbr_modified_at' => null,
        ];

        $details = array_merge($details, $this->parseDetailGrid($xpath));
        $details['exam_results'] = $this->parseExamResults($xpath);
        $details['coordinates'] = $this->parseCoordinates($xpath);
        $details['cbr_modified_at'] = $this->parseCbrModifiedAt($xpath);

        if ($details['address_raw'] !== '') {
            $address = $this->parseAddress($details['address_raw']);
            $details['street'] = $address['street'];
            $details['postal_code'] = $address['postal_code'];
            $details['city'] = $address['city'];
        }

        return $details;
    }

    private function fetchSearchResultsHtml(string $searchUrl, int $from): string
    {
        $url = $searchUrl;
        $separator = str_contains($url, '?') ? '&' : '?';
        $url .= $separator . 'from=' . $from;

        return $this->fetch($url);
    }

    private function parseDetailGrid(DOMXPath $xpath): array
    {
        $data = [
            'address_raw' => '',
            'phone' => '',
            'email' => '',
            'website' => '',
            'praktijkopleidingen' => [],
            'theorieopleidingen' => [],
            'beroepsopleidingen' => [],
            'bijzonderheden' => [],
            'rijschoolnummer' => '',
            'kvk_nummer' => '',
        ];

        $grid = $xpath->query("//div[contains(@class,'detail-grid')]")->item(0);
        if (!$grid instanceof DOMNode) {
            return $data;
        }

        foreach ($xpath->query(".//h4", $grid) as $heading) {
            $label = $this->cleanText($heading->textContent);
            $next = $this->nextElementSibling($heading);
            if (!$next instanceof DOMNode) {
                continue;
            }

            if ($label === 'Adresgegevens' && $next->nodeName === 'p') {
                $data['address_raw'] = $this->cleanText($next->textContent);
                continue;
            }

            if ($label === 'Bereikbaar via') {
                $contact = $this->collectContactLinks($next);
                $data['phone'] = $contact['phone'] ?? '';
                $data['email'] = $contact['email'] ?? '';
                $data['website'] = $contact['website'] ?? '';
                continue;
            }

            if ($label === 'Rijschoolnummer' && $next->nodeName === 'p') {
                $data['rijschoolnummer'] = $this->cleanText($next->textContent);
                continue;
            }

            if ($label === 'KvK-nummer' && $next->nodeName === 'p') {
                $data['kvk_nummer'] = $this->cleanText($next->textContent);
                continue;
            }

            if (in_array($label, ['Praktijkopleidingen', 'Theorieopleidingen', 'Beroepsopleidingen', 'Bijzonderheden'], true)) {
                $items = [];
                if ($next->nodeName === 'ul') {
                    foreach ($xpath->query(".//li", $next) as $li) {
                        $items[] = $this->cleanText($li->textContent);
                    }
                } elseif ($next->nodeName === 'div' && $this->hasClass($next, 'detail-grid__empty')) {
                    $items = [];
                }

                if ($label === 'Praktijkopleidingen') {
                    $data['praktijkopleidingen'] = $items;
                } elseif ($label === 'Theorieopleidingen') {
                    $data['theorieopleidingen'] = $items;
                } elseif ($label === 'Beroepsopleidingen') {
                    $data['beroepsopleidingen'] = $items;
                } elseif ($label === 'Bijzonderheden') {
                    $data['bijzonderheden'] = $items;
                }
            }
        }

        return $data;
    }

    private function collectContactLinks(DOMNode $start): array
    {
        $contact = [
            'phone' => '',
            'email' => '',
            'website' => '',
        ];

        $node = $start;

        while ($node instanceof DOMNode && $node->nodeName === 'a') {
            $class = $node->attributes->getNamedItem('class')->nodeValue ?? '';
            $text = $this->cleanText($node->textContent);

            if (str_contains($class, 'details__contact--phone')) {
                $contact['phone'] = $text;
            } elseif (str_contains($class, 'details__contact--email')) {
                $contact['email'] = $text;
            } elseif (str_contains($class, 'details__contact--website')) {
                $href = $node->attributes->getNamedItem('href')->nodeValue ?? '';
                $contact['website'] = $href !== '' ? $href : $text;
            }

            $node = $this->nextElementSibling($node);
        }

        return $contact;
    }

    private function parseExamResults(DOMXPath $xpath): array
    {
        $results = [];
        $nodes = $xpath->query("//div[contains(@class,'results-container')]//div[contains(@class,'results')]");

        foreach ($nodes as $node) {
            $vehicleCode = $xpath->evaluate("string(.//span[@data-vehicle]/@data-vehicle)", $node);
            $vehicleLabel = $this->cleanText($xpath->evaluate("string(.//span[@data-vehicle])", $node));
            $progress = $xpath->evaluate("string(.//div[contains(@class,'pie-chart')]/@data-progress)", $node);
            $progress = $progress !== '' ? (int) $progress : null;

            $locations = [];
            foreach ($xpath->query(".//p[contains(@class,'result__summary__locations')]//a", $node) as $loc) {
                $locations[] = $this->cleanText($loc->textContent);
            }

            $summaryLines = [];
            foreach ($xpath->query(".//p[contains(@class,'result__summary__line')]", $node) as $line) {
                $value = $this->cleanText($xpath->evaluate("string(.//strong[contains(@class,'result__summary__num')])", $line));
                $label = $this->cleanText(str_replace($value, '', $line->textContent));
                $summaryLines[] = [
                    'label' => $label,
                    'value' => $value,
                ];
            }

            $stats = [];
            foreach ($xpath->query(".//table[contains(@class,'result__statistics')]//tbody//tr", $node) as $row) {
                $cells = $xpath->query("./td", $row);
                if ($cells->length < 5) {
                    continue;
                }

                $location = $this->cleanText($cells->item(0)->textContent);
                $schoolRate = $this->parsePercent($cells->item(1)->textContent);
                $firstRate = $this->parsePercent($cells->item(2)->textContent);
                $firstCount = $this->parseCount($cells->item(2)->textContent);
                $retakeRate = $this->parsePercent($cells->item(3)->textContent);
                $retakeCount = $this->parseCount($cells->item(3)->textContent);
                $locationRate = $this->parsePercent($cells->item(4)->textContent);

                $stats[] = [
                    'location' => $location,
                    'pass_rate_school' => $schoolRate,
                    'pass_rate_first' => $firstRate,
                    'first_exam_count' => $firstCount,
                    'pass_rate_retake' => $retakeRate,
                    'retake_exam_count' => $retakeCount,
                    'pass_rate_location' => $locationRate,
                ];
            }

            $results[] = [
                'vehicle_code' => $vehicleCode,
                'vehicle_label' => $vehicleLabel,
                'progress' => $progress,
                'locations' => $locations,
                'summary' => $summaryLines,
                'stats' => $stats,
            ];
        }

        return $results;
    }

    private function parseCoordinates(DOMXPath $xpath): array
    {
        $coordinates = [];

        foreach ($xpath->query("//meta[starts-with(@name,'coordinates')]") as $meta) {
            $content = $meta->attributes->getNamedItem('content')->nodeValue ?? '';
            if ($content === '') {
                continue;
            }

            $parts = array_map('trim', explode(',', $content));
            if (count($parts) !== 2) {
                continue;
            }

            if (!is_numeric($parts[0]) || !is_numeric($parts[1])) {
                continue;
            }

            $coordinates[] = [
                'lat' => (float) $parts[0],
                'lng' => (float) $parts[1],
            ];
        }

        return $coordinates;
    }

    private function parseCbrModifiedAt(DOMXPath $xpath): ?Carbon
    {
        $modified = $xpath->evaluate("string(//meta[@name='dcterms:modified']/@content)");
        if ($modified === '') {
            $modified = $xpath->evaluate("string(//meta[@name='content_last_modified_date']/@content)");
        }

        if ($modified === '') {
            return null;
        }

        try {
            return Carbon::parse($modified);
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function parseAddress(string $raw): array
    {
        $raw = $this->cleanText($raw);

        $street = $raw;
        $postalCode = '';
        $city = '';

        if (preg_match('/^(.*?)(\\d{4}\\s?[A-Z]{2})\\s+(.+)$/', $raw, $match)) {
            $street = trim($match[1]);
            $postalCode = str_replace(' ', '', strtoupper(trim($match[2])));
            $city = trim($match[3]);
        }

        return [
            'street' => $street,
            'postal_code' => $postalCode,
            'city' => $city,
        ];
    }

    private function parsePercent(string $text): ?int
    {
        if (preg_match('/(\\d+)%/', $text, $match)) {
            return (int) $match[1];
        }

        return null;
    }

    private function parseCount(string $text): ?int
    {
        if (preg_match('/\\((\\d+)\\)/', $text, $match)) {
            return (int) $match[1];
        }

        return null;
    }

    private function persistSchool(string $url, array $details, bool $dryRun): void
    {
        if ($dryRun) {
            return;
        }

        $slug = $this->slugFromUrl($url);

        Rijschool::updateOrCreate(
            ['cbr_url' => $url],
            [
                'name' => $details['name'] ?? '',
                'email' => $details['email'] ?? '',
                'phone' => $details['phone'] ?? '',
                'website' => $details['website'] ?? '',
                'address_raw' => $details['address_raw'] ?? '',
                'street' => $details['street'] ?? '',
                'postal_code' => $details['postal_code'] ?? '',
                'city' => $details['city'] ?? '',
                'rijschoolnummer' => $details['rijschoolnummer'] ?? '',
                'kvk_nummer' => $details['kvk_nummer'] ?? '',
                'praktijkopleidingen' => $details['praktijkopleidingen'] ?? [],
                'theorieopleidingen' => $details['theorieopleidingen'] ?? [],
                'beroepsopleidingen' => $details['beroepsopleidingen'] ?? [],
                'bijzonderheden' => $details['bijzonderheden'] ?? [],
                'exam_results' => $details['exam_results'] ?? [],
                'coordinates' => $details['coordinates'] ?? [],
                'cbr_modified_at' => $details['cbr_modified_at'] ?? null,
                'cbr_slug' => $slug,
                'crawled_at' => Carbon::now(),
            ]
        );
    }

    private function fetch(string $pathOrUrl): string
    {
        $url = $this->toAbsoluteUrl($pathOrUrl);

        $response = Http::withHeaders([
            'User-Agent' => 'DeVerkeersbordenCrawler/1.0 (+https://deverkeersborden.nl)',
        ])
            ->retry(3, 250)
            ->timeout(20)
            ->get($url);

        if (!$response->successful()) {
            throw new RuntimeException("HTTP {$response->status()} voor {$url}");
        }

        return $response->body();
    }

    private function toAbsoluteUrl(string $url): string
    {
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        return rtrim(self::BASE_URL, '/') . '/' . ltrim($url, '/');
    }

    private function slugFromUrl(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path) {
            return '';
        }

        $path = trim($path, '/');
        $parts = explode('/', $path);

        return end($parts) ?: '';
    }

    private function makeXPath(string $html): DOMXPath
    {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        return new DOMXPath($dom);
    }

    private function cleanText(string $text): string
    {
        $text = str_replace("\xc2\xa0", ' ', $text);
        $text = preg_replace('/\\s+/', ' ', $text) ?? '';

        return trim($text);
    }

    private function nextElementSibling(DOMNode $node): ?DOMNode
    {
        $next = $node->nextSibling;
        while ($next && $next->nodeType !== XML_ELEMENT_NODE) {
            $next = $next->nextSibling;
        }

        return $next instanceof DOMNode ? $next : null;
    }

    private function hasClass(DOMNode $node, string $class): bool
    {
        $classes = $node->attributes->getNamedItem('class')->nodeValue ?? '';
        $list = preg_split('/\\s+/', $classes) ?: [];

        return in_array($class, $list, true);
    }

    private function sleep(int $sleepMs): void
    {
        if ($sleepMs <= 0) {
            return;
        }

        usleep($sleepMs * 1000);
    }

    private function log(?callable $progress, string $message): void
    {
        if ($progress !== null) {
            $progress($message);
        }
    }
}
