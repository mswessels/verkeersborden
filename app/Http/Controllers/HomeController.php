<?php namespace App\Http\Controllers;

use App\SignCategory;
use Illuminate\Support\Str;

class HomeController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{		
		$meta = array(
			'meta_title' => 'Verkeersborden oefenen (gratis quiz) | DeVerkeersborden.nl',
			'meta_description' => 'Oefen verkeersborden met een gratis quiz. Leer betekenissen, tips en ezelsbruggetjes en bereid je slim voor op je theorie-examen (CBR).',
			'canonical' => url('/'),
			'meta_image' => asset('img/automobile-2679744_640.jpg'),
			'meta_type' => 'website',
		);
		
		return view('home' ,$meta);
	}
	
	public function alleBorden()
	{
		$query = trim(request('q', ''));
		$like = $query !== '' ? '%' . $query . '%' : null;

		$categories = SignCategory::with(['signs' => function($queryBuilder) use ($like) {
			if ($like) {
				$queryBuilder->where(function($inner) use ($like) {
					$inner->where('code', 'like', $like)
						->orWhere('description', 'like', $like)
						->orWhere('description_short', 'like', $like);
				});
			}

			$queryBuilder->orderBy('code');
		}])->orderBy('letter')->get();

		if ($query !== '') {
			$categories = $categories->filter(function ($category) {
				return $category->signs->count() > 0;
			})->values();
		}

		$meta = array(
			'meta_title' => 'Alle Verkeersborden',
			'meta_description' => 'Alle verkeersborden van Nederland in een handig overzicht. Bij elkaar hebben we in ons land bijna 200 verschillende borden, bekijk hier alle verkeersborden.',
			'canonical' => url('/alle-verkeersborden'),
			'meta_image' => asset('img/kaart.png'),
			'meta_type' => 'website',
			'breadcrumbs' => [
				['label' => 'Home', 'url' => url('/')],
				['label' => 'Alle verkeersborden', 'url' => null],
			],
			'categories' => $categories,
			'search_query' => $query,
		);
		
		return view('alleborden' ,$meta);
	}
	
	public function theorieExamen()
	{
		$meta = array(
			'meta_title' => 'Gratis CBR Theorie Examen Oefenen',
			'meta_description' => 'Test je kennis van de verkeersborden, doe een gratis test. Wij hebben alle verkeersborden die in Nederland te vinden zijn in een gratis verkeersborden oefening.',
			'canonical' => url('/theorie-examen-oefenen'),
			'meta_image' => asset('img/kaart.png'),
			'meta_type' => 'website',
			'breadcrumbs' => [
				['label' => 'Home', 'url' => url('/')],
				['label' => 'Theorie examen oefenen', 'url' => null],
			],
		);
		
		return view('theorie' ,$meta);
	}
	
	public function links()
	{
		$meta = array(
			'meta_title' => 'Linkpartners',
			'meta_description' => 'Links van onze partners vindt je op een handige pagina. Opzoek naar een rijschool in de regio? Bekijk partners bij jouw in de buurt. Link ruilen?',
			'canonical' => url('/links'),
			'meta_image' => asset('img/kaart.png'),
			'meta_type' => 'website',
			'breadcrumbs' => [
				['label' => 'Home', 'url' => url('/')],
				['label' => 'Links', 'url' => null],
			],
		);
		
		return view('links' ,$meta);
	}

	public function serie($letter)
	{
		$letter = Str::upper($letter);
		$category = SignCategory::where('letter', $letter)->first();

		if (!$category) {
			abort(404);
		}

		$signs = $category->signs()->orderBy('code')->get();
		$allCategories = SignCategory::orderBy('letter')->get();
		$seriesInfo = $this->seriesInfo($letter, $category->name);
		$canonical = url('/verkeersborden/serie-' . Str::lower($letter));

		$meta = array(
			'meta_title' => 'Serie ' . $letter . ' - ' . $category->name . ' verkeersborden',
			'meta_description' => $seriesInfo['meta_description'],
			'canonical' => $canonical,
			'meta_image' => asset('img/kaart.png'),
			'meta_type' => 'website',
			'breadcrumbs' => [
				['label' => 'Home', 'url' => url('/')],
				['label' => 'Alle verkeersborden', 'url' => url('/alle-verkeersborden')],
				['label' => 'Serie ' . $letter . ' - ' . $category->name, 'url' => null],
			],
			'category' => $category,
			'signs' => $signs,
			'all_categories' => $allCategories,
			'series_info' => $seriesInfo,
		);

		return view('series', $meta);
	}

	private function seriesInfo(string $letter, string $name): array
	{
		$map = [
			'A' => [
				'meta' => 'Alles over snelheidsborden en adviessnelheid. Leer de verschillen, herken einde-borden en oefen gericht.',
				'tips' => [
					'Let op verschil tussen maximumsnelheid en adviessnelheid.',
					'Einde-borden herken je aan de grijze streep of grijze rand.',
					'Matrixborden gelden alleen als ze zijn ingeschakeld.',
				],
			],
			'B' => [
				'meta' => 'Voorrangsborden snel herkennen. Leer wie er voorrang heeft en wanneer je moet stoppen.',
				'tips' => [
					'Omgekeerde driehoek betekent: jij moet voorrang geven.',
					'STOP is altijd volledig stoppen, ook als de weg vrij is.',
					'Let op onderborden met uitzonderingen of tijden.',
				],
			],
			'C' => [
				'meta' => 'Geslotenverklaringen voor voertuigen. Leer wie er wel of niet mag inrijden.',
				'tips' => [
					'Rood rond bord betekent verbod, kijk goed welk voertuig is afgebeeld.',
					'Let op varianten met aanhangwagen of voertuiggewicht.',
					'Bekijk altijd het einde-bord van een geslotenverklaring.',
				],
			],
			'D' => [
				'meta' => 'Verplichte rijrichtingen en rotonde-borden. Handig voor juiste rijrichting en voorsorteren.',
				'tips' => [
					'Blauw rond betekent verplicht: volg de pijlen.',
					'Let op borden met meerdere pijlen voor keuze richtingen.',
					'Combineer dit met markeringen op de weg.',
				],
			],
			'E' => [
				'meta' => 'Parkeren en stilstaan. Leer het verschil tussen parkeerverbod en stilstaanverbod.',
				'tips' => [
					'E1 is parkeerverbod, E2 is stilstaanverbod.',
					'Lees onderborden voor tijden en uitzonderingen.',
					'Zone-borden gelden tot het einde-zone bord.',
				],
			],
			'F' => [
				'meta' => 'Overige geboden en verboden. Belangrijk voor inhalen, keren en voorrang op smalle wegen.',
				'tips' => [
					'F1/F2 gaan over inhaalverbod en einde ervan.',
					'F5/F6 regelen voorrang op smalle wegen.',
					'F7 is keerverbod: niet keren.',
				],
			],
			'G' => [
				'meta' => 'Verkeersregels en plaats op de weg. Denk aan autosnelweg, erf en fietspaden.',
				'tips' => [
					'G1 en G2 geven begin en einde autosnelweg aan.',
					'G5 en G6 horen bij erfregels.',
					'Let op verschil verplicht en onverplicht fietspad.',
				],
			],
			'H' => [
				'meta' => 'Bebouwde kom borden. Ze bepalen de standaard snelheid en regels in en buiten de kom.',
				'tips' => [
					'H1 start bebouwde kom, H2 eindigt de kom.',
					'Na H2 gelden vaak hogere snelheden.',
					'Blijf alert op snelheidsborden in de kom.',
				],
			],
			'J' => [
				'meta' => 'Waarschuwingsborden voor gevaren. Leer snel anticiperen op situaties.',
				'tips' => [
					'Driehoekig bord betekent opletten en snelheid aanpassen.',
					'Borden lijken soms op elkaar, let op het pictogram.',
					'Onderborden kunnen de aard van het gevaar toelichten.',
				],
			],
			'K' => [
				'meta' => 'Bewegwijzering en routeborden. Helpt je navigeren op snelweg en binnen de kom.',
				'tips' => [
					'Let op verschil tussen voorwegwijzer en beslissingswegwijzer.',
					'Symbolen geven voorzieningen aan zoals parkeerplaats of luchthaven.',
					'Kleuren en vormen helpen bij herkennen van wegtype.',
				],
			],
			'L' => [
				'meta' => 'Informatieborden zoals hoogte, tunnelaanduiding en rijstrookinformatie.',
				'tips' => [
					'L1 geeft hoogte onderdoorgang aan, belangrijk voor hoge voertuigen.',
					'L2 is voetgangersoversteekplaats, vaak bij zebra.',
					'Let op borden voor rijstrookindeling en splitsingen.',
				],
			],
		];

		$details = $map[$letter] ?? [
			'meta' => 'Bekijk alle verkeersborden uit serie ' . $letter . ' en leer de belangrijkste betekenissen en regels.',
			'tips' => [
				'Herhaal de borden regelmatig om ze snel te herkennen.',
				'Let op onderborden die uitzonderingen aangeven.',
				'Oefen met situaties waarin deze borden voorkomen.',
			],
		];

		return [
			'title' => 'Serie ' . $letter . ' - ' . $name,
			'intro' => 'Op deze pagina vind je alle verkeersborden uit serie ' . $letter . ' (' . $name . '). Klik op een bord voor betekenis, ezelsbruggetje en oefenvragen.',
			'meta_description' => $details['meta'],
			'tips' => $details['tips'],
		];
	}

}
