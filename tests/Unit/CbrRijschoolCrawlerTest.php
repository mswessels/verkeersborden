<?php

namespace Tests\Unit;

use App\Services\CbrRijschoolCrawler;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class CbrRijschoolCrawlerTest extends TestCase
{
    public function test_extract_search_url(): void
    {
        $crawler = new CbrRijschoolCrawler();
        $html = $this->fixture('cbr_search_page.html');

        $url = $crawler->extractSearchUrl($html);

        $this->assertSame(
            'https://www.cbr.nl/web/show?id=313642&langid=43&query=a&sign=abc123',
            $url
        );
    }

    public function test_extract_search_links_and_count(): void
    {
        $crawler = new CbrRijschoolCrawler();
        $html = $this->fixture('cbr_search_results.html');

        $count = $crawler->extractResultCount($html);
        $links = $crawler->extractSchoolLinks($html);

        $this->assertSame(12, $count);
        $this->assertSame(
            [
                'https://www.cbr.nl/nl/service/nl/rijscholen/a-one-autorijopleiding',
                'https://www.cbr.nl/nl/service/nl/rijscholen/rijschool-a2-a15',
            ],
            $links
        );
    }

    public function test_parse_school_details(): void
    {
        $crawler = new CbrRijschoolCrawler();
        $html = $this->fixture('cbr_detail_page.html');

        $details = $crawler->parseSchoolDetails($html);

        $this->assertSame('A-One Autorijopleiding', $details['name']);
        $this->assertSame('Belvederebos 91 2715VE ZOETERMEER', $details['address_raw']);
        $this->assertSame('Belvederebos 91', $details['street']);
        $this->assertSame('2715VE', $details['postal_code']);
        $this->assertSame('ZOETERMEER', $details['city']);
        $this->assertSame('(079) 320 06 68', $details['phone']);
        $this->assertSame('info@a1-rijbewijs.nl', $details['email']);
        $this->assertSame('https://a1-rijschool.nl', $details['website']);
        $this->assertSame(['Auto'], $details['praktijkopleidingen']);
        $this->assertSame(['Auto'], $details['theorieopleidingen']);
        $this->assertSame([], $details['beroepsopleidingen']);
        $this->assertSame(['Personenauto met Automaat'], $details['bijzonderheden']);
        $this->assertSame('0827Z8', $details['rijschoolnummer']);
        $this->assertSame('27191609', $details['kvk_nummer']);

        $this->assertCount(2, $details['coordinates']);
        $this->assertEqualsWithDelta(52.05767974, $details['coordinates'][0]['lat'], 0.0000001);
        $this->assertEqualsWithDelta(4.47652466, $details['coordinates'][0]['lng'], 0.0000001);

        $this->assertInstanceOf(Carbon::class, $details['cbr_modified_at']);
        $this->assertSame('2025-10-08', $details['cbr_modified_at']->toDateString());

        $this->assertCount(1, $details['exam_results']);
        $exam = $details['exam_results'][0];
        $this->assertSame('B', $exam['vehicle_code']);
        $this->assertSame('Auto', $exam['vehicle_label']);
        $this->assertSame(67, $exam['progress']);
        $this->assertSame(['Zoetermeer'], $exam['locations']);
        $this->assertCount(2, $exam['summary']);
        $this->assertSame('totaal aantal afgenomen eerste examens', $exam['summary'][0]['label']);
        $this->assertSame('39', $exam['summary'][0]['value']);
        $this->assertCount(1, $exam['stats']);
        $this->assertSame('Zoetermeer', $exam['stats'][0]['location']);
        $this->assertSame(61, $exam['stats'][0]['pass_rate_school']);
        $this->assertSame(67, $exam['stats'][0]['pass_rate_first']);
        $this->assertSame(39, $exam['stats'][0]['first_exam_count']);
        $this->assertSame(50, $exam['stats'][0]['pass_rate_retake']);
        $this->assertSame(20, $exam['stats'][0]['retake_exam_count']);
        $this->assertSame(45, $exam['stats'][0]['pass_rate_location']);
    }

    private function fixture(string $name): string
    {
        $path = dirname(__DIR__) . '/fixtures/' . $name;

        return file_get_contents($path) ?: '';
    }
}
