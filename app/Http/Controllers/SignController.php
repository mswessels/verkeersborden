<?php namespace App\Http\Controllers;

use App\Sign;
use App\Services\SignTextGenerator;
use Illuminate\Support\Str;

class SignController extends Controller
{
	public function show($codeSlug)
	{
		$codeSlug = trim($codeSlug);
		$code = $this->extractCode($codeSlug);

		if (!$code) {
			abort(404);
		}

		$sign = Sign::where('code', $code)->first();
		if (!$sign) {
			abort(404);
		}

		$slug = $sign->slug ?: Str::slug($sign->code);
		$canonical = $sign->code . '-' . $slug;

		if (Str::lower($codeSlug) !== Str::lower($canonical)) {
			return redirect('/verkeersborden/' . $canonical, 301);
		}

		$categoryLetter = Str::upper(substr($sign->code, 0, 1));
		$categoryName = $this->categoryName($categoryLetter);

		$generator = new SignTextGenerator();
		$meaning = $this->valueOrFallback($sign->meaning ?? null, $generator->buildMeaning($sign));
		$mnemonic = $this->valueOrFallback($sign->mnemonic ?? null, $generator->buildMnemonic($sign));
		$mistake = $this->valueOrFallback($sign->mistake ?? null, $generator->buildMistake($sign));

		$storedQuestions = $sign->practice_questions;
		$questions = is_array($storedQuestions) && count($storedQuestions) > 0
			? $storedQuestions
			: $generator->buildQuestions($sign);

		$scenario = $generator->buildScenario($sign);
		$checklist = $generator->buildChecklist($sign);
		$answers = $generator->buildModelAnswers($sign, $questions, $meaning, $mistake, $checklist);
		$faqItems = $generator->buildFaq($sign, $meaning, $mistake, $checklist);
		$faqJson = $this->buildFaqJson($faqItems);

		$breadcrumbs = $this->buildBreadcrumbs($sign, $categoryLetter, $categoryName);
		[$prevSign, $nextSign] = $this->buildPrevNext($sign);
		$relatedSigns = $this->buildRelatedSigns($sign);

		$meta = [
			'meta_title' => 'Verkeersbord ' . $sign->code . ': ' . $sign->description,
			'meta_description' => 'Betekenis van verkeersbord ' . $sign->code . ' (' . $sign->description . '). Uitleg, ezelsbruggetje, veelgemaakte fout en oefenvragen.',
		];

		return view('sign', $meta + [
			'sign' => $sign,
			'category_letter' => $categoryLetter,
			'category_name' => $categoryName,
			'meaning' => $meaning,
			'mnemonic' => $mnemonic,
			'mistake' => $mistake,
			'questions' => $questions,
			'answers' => $answers,
			'scenario' => $scenario,
			'checklist' => $checklist,
			'faq_items' => $faqItems,
			'faq_json' => $faqJson,
			'breadcrumbs' => $breadcrumbs,
			'prev_sign' => $prevSign,
			'next_sign' => $nextSign,
			'related_signs' => $relatedSigns,
		]);
	}

	private function extractCode(string $codeSlug): ?string
	{
		if (preg_match('/^([a-z]\\d+(?:-\\d+)?[a-z]?)/i', $codeSlug, $matches)) {
			return Str::upper($matches[1]);
		}

		return null;
	}

	private function categoryName(string $letter): string
	{
		$map = [
			'A' => 'Snelheid',
			'B' => 'Voorrang',
			'C' => 'Geslotenverklaring',
			'D' => 'Rijrichting',
			'E' => 'Parkeren en stilstaan',
			'F' => 'Overige geboden en verboden',
			'G' => 'Verkeersregels',
			'H' => 'Bebouwde kom',
			'J' => 'Waarschuwing',
			'K' => 'Bewegwijzering',
			'L' => 'Informatie',
		];

		return $map[$letter] ?? 'Verkeersborden';
	}

	private function valueOrFallback(?string $value, string $fallback): string
	{
		$value = $value ? trim($value) : '';

		return $value !== '' ? $value : $fallback;
	}

	private function buildFaqJson(array $faqItems): string
	{
		$entities = [];

		foreach ($faqItems as $item) {
			if (!isset($item['question'], $item['answer'])) {
				continue;
			}

			$entities[] = [
				'@type' => 'Question',
				'name' => $item['question'],
				'acceptedAnswer' => [
					'@type' => 'Answer',
					'text' => $item['answer'],
				],
			];
		}

		$payload = [
			'@context' => 'https://schema.org',
			'@type' => 'FAQPage',
			'mainEntity' => $entities,
		];

		return json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

	private function buildBreadcrumbs(Sign $sign, string $categoryLetter, string $categoryName): array
	{
		$seriesAnchor = url('/alle-verkeersborden#serie-' . Str::lower($categoryLetter));

		return [
			['label' => 'Home', 'url' => url('/')],
			['label' => 'Alle verkeersborden', 'url' => url('/alle-verkeersborden')],
			['label' => 'Serie ' . $categoryLetter . ' - ' . $categoryName, 'url' => $seriesAnchor],
			['label' => 'Bord ' . $sign->code, 'url' => null],
		];
	}

	private function buildPrevNext(Sign $sign): array
	{
		$allSigns = Sign::all();
		$sorted = $allSigns->all();

		usort($sorted, function ($left, $right) {
			return $this->compareCodes($left->code, $right->code);
		});

		$codes = array_map(function ($item) {
			return $item->code;
		}, $sorted);

		$index = array_search($sign->code, $codes, true);
		if ($index === false) {
			return [null, null];
		}

		$prev = $index > 0 ? $sorted[$index - 1] : null;
		$next = $index < count($sorted) - 1 ? $sorted[$index + 1] : null;

		return [$prev, $next];
	}

	private function compareCodes(string $left, string $right): int
	{
		$leftKey = $this->codeSortKey($left);
		$rightKey = $this->codeSortKey($right);

		foreach ([0, 1, 2, 3] as $index) {
			if ($leftKey[$index] === $rightKey[$index]) {
				continue;
			}

			return $leftKey[$index] <=> $rightKey[$index];
		}

		return 0;
	}

	private function codeSortKey(string $code): array
	{
		$code = Str::upper($code);
		$letter = $code;
		$number = 0;
		$dash = -1;
		$suffix = '';

		if (preg_match('/^([A-Z])(\\d+)(?:-([0-9]+))?([A-Z])?$/', $code, $matches)) {
			$letter = $matches[1];
			$number = (int) $matches[2];
			if (isset($matches[3]) && $matches[3] !== '') {
				$dash = (int) $matches[3];
			}
			$suffix = $matches[4] ?? '';
		}

		return [$letter, $number, $dash, $suffix];
	}

	private function buildRelatedSigns(Sign $sign, int $limit = 6): array
	{
		$codes = $this->manualRelatedMap()[$sign->code] ?? [];
		$related = collect();

		if ($codes) {
			$related = Sign::whereIn('code', $codes)->get();
		}

		if ($related->count() < $limit) {
			$fill = Sign::where('category_id', $sign->category_id)
				->where('code', '<>', $sign->code)
				->whereNotIn('code', $related->pluck('code')->all())
				->orderBy('code')
				->take($limit - $related->count())
				->get();

			$related = $related->concat($fill);
		}

		return $related->all();
	}

	private function manualRelatedMap(): array
	{
		return [
			'A1' => ['A2', 'A3', 'A4', 'A5'],
			'A2' => ['A1', 'A3'],
			'A3' => ['A1', 'A2'],
			'A4' => ['A5', 'A1'],
			'A5' => ['A4', 'A1'],
			'B1' => ['B2', 'B6', 'B7'],
			'B2' => ['B1'],
			'B3' => ['B4', 'B5'],
			'B4' => ['B3', 'B5'],
			'B5' => ['B3', 'B4'],
			'B6' => ['B7', 'B1'],
			'B7' => ['B6', 'B1'],
			'C1' => ['C2', 'C12'],
			'C2' => ['C1', 'C3'],
			'C3' => ['C4'],
			'C4' => ['C3'],
			'C7' => ['C7a', 'C7b'],
			'C7a' => ['C7', 'C7b'],
			'C7b' => ['C7', 'C7a'],
			'C9' => ['C10', 'C11'],
			'C10' => ['C9', 'C11'],
			'C11' => ['C9', 'C10'],
			'C13' => ['C14', 'C15'],
			'C14' => ['C13', 'C15'],
			'C15' => ['C13', 'C14'],
			'C23-01' => ['C23-02', 'C23-03'],
			'C23-02' => ['C23-01', 'C23-03'],
			'C23-03' => ['C23-01', 'C23-02'],
			'D1' => ['D2', 'D3'],
			'D2' => ['D3', 'D1'],
			'D3' => ['D2', 'D1'],
			'D4' => ['D5', 'D6'],
			'D5' => ['D4', 'D6'],
			'D6' => ['D7', 'D4'],
			'D7' => ['D6'],
			'E1' => ['E2', 'E3'],
			'E2' => ['E1', 'E3'],
			'E3' => ['E1', 'E2'],
			'E4' => ['E8', 'E9'],
			'E8' => ['E4', 'E9'],
			'E9' => ['E4', 'E8'],
			'E10' => ['E11'],
			'E11' => ['E10'],
			'F1' => ['F2', 'F3'],
			'F2' => ['F1'],
			'F3' => ['F4', 'F1'],
			'F4' => ['F3'],
			'F5' => ['F6'],
			'F6' => ['F5'],
			'F7' => ['F8'],
			'F8' => ['F7', 'F9'],
			'F9' => ['F8'],
			'G1' => ['G2', 'G3'],
			'G2' => ['G1', 'G3'],
			'G3' => ['G4', 'G1'],
			'G4' => ['G3'],
			'G5' => ['G6'],
			'G6' => ['G5'],
			'G7' => ['G8'],
			'G8' => ['G7'],
			'G9' => ['G10'],
			'G10' => ['G9'],
			'G11' => ['G12a', 'G12b', 'G13'],
			'G12a' => ['G11', 'G12b', 'G13'],
			'G12b' => ['G11', 'G12a', 'G13'],
			'G13' => ['G11', 'G12a'],
			'H1' => ['H2'],
			'H2' => ['H1'],
			'J1' => ['J2', 'J3', 'J25', 'J38'],
			'J2' => ['J1', 'J3', 'J4'],
			'J3' => ['J1', 'J2', 'J5'],
			'J4' => ['J5', 'J2'],
			'J5' => ['J4', 'J3'],
			'J6' => ['J7'],
			'J7' => ['J6'],
			'J8' => ['J9', 'J14'],
			'J9' => ['J8', 'J14'],
			'J10' => ['J11', 'J12', 'J13'],
			'J11' => ['J10', 'J12', 'J13'],
			'J12' => ['J10', 'J11', 'J13'],
			'J13' => ['J10', 'J11', 'J12'],
			'J14' => ['J8', 'J10', 'J11'],
			'J15' => ['J26', 'J37'],
			'J16' => ['J37', 'J38'],
			'J17' => ['J18', 'J19', 'J29'],
			'J18' => ['J17', 'J19', 'J29'],
			'J19' => ['J17', 'J18', 'J29'],
			'J20' => ['J31', 'J35', 'J36'],
			'J21' => ['J22', 'J23', 'J24'],
			'J22' => ['J21', 'J23', 'L2'],
			'J23' => ['J21', 'J22', 'J24'],
			'J24' => ['J21', 'J23', 'J22'],
			'J25' => ['J24', 'J1', 'J38'],
			'J26' => ['J15', 'J37'],
			'J27' => ['J28'],
			'J28' => ['J27'],
			'J29' => ['J17', 'J18', 'J19'],
			'J30' => ['J31', 'J37'],
			'J31' => ['J20', 'J30'],
			'J32' => ['J33', 'J34'],
			'J33' => ['J32', 'J34'],
			'J34' => ['J33', 'J32'],
			'J35' => ['J36', 'J20'],
			'J36' => ['J35', 'J20'],
			'J37' => ['J15', 'J16', 'J39'],
			'J38' => ['J1', 'J16', 'J25'],
			'J39' => ['J37', 'J15'],
			'K1' => ['K2', 'K4', 'K3'],
			'K2' => ['K1', 'K4', 'K3'],
			'K3' => ['K1', 'K2', 'K4'],
			'K4' => ['K1', 'K2', 'K3'],
			'K5' => ['K6'],
			'K6' => ['K5'],
			'K7' => ['K8'],
			'K8' => ['K7'],
			'K10' => ['K11'],
			'K11' => ['K10'],
			'K12' => ['K13'],
			'K13' => ['K12'],
			'K14' => ['K5', 'K6'],
			'L1' => ['L2', 'L3'],
			'L2' => ['L1', 'L3', 'J22'],
			'L3' => ['L1', 'L2'],
		];
	}
}
