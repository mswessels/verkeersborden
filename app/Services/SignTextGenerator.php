<?php namespace App\Services;

use App\Sign;
use Illuminate\Support\Str;

class SignTextGenerator
{
    public function buildMeaning(Sign $sign): string
    {
        $description = trim($sign->description ?? '');
        $letter = $this->categoryLetter($sign);

        if ($description === '') {
            $description = 'Onbekende betekenis';
        }

        $meaning = $description . '. ' . $this->categoryMeaning($letter);

        if ($this->isEndSign($description)) {
            $meaning .= ' Na dit bord geldt de eerdere regel niet meer.';
        } elseif ($this->contains($description, ['zone'])) {
            $meaning .= ' Let op de zone, het bord geldt totdat het einde wordt aangegeven.';
        } elseif ($this->contains($description, ['verleen voorrang', 'voorrang'])) {
            $meaning .= ' Bepaal direct wie er eerst mag en handel daarnaar.';
        } elseif ($this->contains($description, ['stop'])) {
            $meaning .= ' Je moet volledig stoppen en voorrang verlenen waar nodig.';
        } elseif ($this->contains($description, ['adviessnelheid'])) {
            $meaning .= ' Het is een advies, maar kies een veilige snelheid.';
        } elseif ($this->contains($description, ['maximumsnelheid'])) {
            $meaning .= ' Het getal is de maximale snelheid die je mag rijden.';
        } elseif ($this->contains($description, ['gesloten', 'verbod'])) {
            $meaning .= ' Als jij tot de genoemde groep hoort, mag je hier niet door.';
        } elseif ($this->contains($description, ['gebod', 'verplichte'])) {
            $meaning .= ' Volg het gebod direct en wijk niet af.';
        } elseif ($this->contains($description, ['parkeren', 'stilstaan'])) {
            $meaning .= ' Kijk of parkeren of alleen stilstaan is toegestaan.';
        } elseif ($this->contains($description, ['waarschuwing', 'let op'])) {
            $meaning .= ' Wees extra alert en pas je snelheid en afstand aan.';
        }

        return $meaning;
    }

    public function buildMnemonic(Sign $sign): string
    {
        $description = Str::lower($sign->description ?? '');
        $letter = $this->categoryLetter($sign);

        $base = $this->categoryMnemonic($letter);

        if ($this->contains($description, ['einde'])) {
            return $base . ' Denk: streep = einde, de regel stopt hier.';
        }

        if ($this->contains($description, ['stop'])) {
            return $base . ' Achthoek = STOP, altijd volledig stoppen.';
        }

        if ($this->contains($description, ['verleen voorrang'])) {
            return $base . ' Driehoek punt omlaag = jij geeft voorrang.';
        }

        if ($this->contains($description, ['voorrangsweg'])) {
            return $base . ' Gele ruit = voorrang op de hoofdweg.';
        }

        if ($this->contains($description, ['parkeren'])) {
            return $base . ' E-serie = parkeren/stilstaan, denk aan P en X.';
        }

        if ($this->contains($description, ['stilstaan'])) {
            return $base . ' Rood kruis betekent: niet stilstaan.';
        }

        if ($this->contains($description, ['verbod', 'gesloten'])) {
            return $base . ' Rood is nee: dit mag niet.';
        }

        if ($this->contains($description, ['gebod', 'verplichte'])) {
            return $base . ' Blauw rond is verplicht: dit moet.';
        }

        if ($this->contains($description, ['waarschuw', 'let op'])) {
            return $base . ' Driehoek = waarschuwing, kijk en vertraag.';
        }

        return $base . ' Koppel vorm en kleur aan de boodschap.';
    }

    public function buildMistake(Sign $sign): string
    {
        $description = Str::lower($sign->description ?? '');
        $letter = $this->categoryLetter($sign);

        if ($this->contains($description, ['einde'])) {
            return 'Veelgemaakte fout: denken dat de regel na dit bord nog geldt.';
        }

        if ($this->contains($description, ['adviessnelheid'])) {
            return 'Veelgemaakte fout: advies verwarren met een verplicht maximum.';
        }

        if ($this->contains($description, ['maximumsnelheid'])) {
            return 'Veelgemaakte fout: de maximumsnelheid zien als advies.';
        }

        if ($this->contains($description, ['stop'])) {
            return 'Veelgemaakte fout: niet volledig stoppen of geen voorrang verlenen.';
        }

        if ($this->contains($description, ['verleen voorrang'])) {
            return 'Veelgemaakte fout: toch doorrijden terwijl je voorrang moet geven.';
        }

        if ($this->contains($description, ['voorrangsweg'])) {
            return 'Veelgemaakte fout: denken dat je overal voorrang hebt en onderborden negeren.';
        }

        if ($letter === 'E') {
            return 'Veelgemaakte fout: parkeren en stilstaan door elkaar halen.';
        }

        if ($letter === 'C') {
            return 'Veelgemaakte fout: denken dat het bord niet voor jouw voertuig geldt.';
        }

        if ($letter === 'J') {
            return 'Veelgemaakte fout: waarschuwingen negeren en snelheid niet aanpassen.';
        }

        return 'Veelgemaakte fout: onderborden overslaan, terwijl die uitzonderingen aangeven.';
    }

    public function buildQuestions(Sign $sign): array
    {
        $description = Str::lower($sign->description ?? '');
        $code = $sign->code;
        $letter = $this->categoryLetter($sign);

        $third = 'Welke uitzonderingen kunnen via onderborden bij bord ' . $code . ' gelden?';

        if ($this->contains($description, ['einde'])) {
            $third = 'Welke regel stopt hier door bord ' . $code . '?';
        } elseif ($letter === 'C') {
            $third = 'Voor welke voertuigen of weggebruikers geldt dit bord?';
        } elseif ($letter === 'J') {
            $third = 'Hoe pas je je snelheid en afstand aan bij deze waarschuwing?';
        } elseif ($letter === 'E') {
            $third = 'Mag je hier parkeren, stilstaan of allebei niet?';
        }

        return [
            'Wat betekent verkeersbord ' . $code . '?',
            'Welk gedrag moet je direct aanpassen als je dit bord ziet?',
            $third,
        ];
    }

    public function buildScenario(Sign $sign): string
    {
        $description = trim($sign->description ?? '');
        $code = $sign->code;
        $letter = $this->categoryLetter($sign);

        if ($description === '') {
            $description = 'dit bord';
        }

        switch ($letter) {
            case 'A':
                return 'Je rijdt op een weg en ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord bepaalt welke snelheid nu geldt.';
            case 'B':
                return 'Je nadert een kruising en ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord bepaalt wie er voorrang heeft.';
            case 'C':
                return 'Je wilt een weg inrijden en ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord bepaalt voor wie de weg gesloten is.';
            case 'D':
                return 'Je komt bij een splitsing en ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord geeft de verplichte rijrichting aan.';
            case 'E':
                return 'Je zoekt een plek om te parkeren en ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord bepaalt of je mag parkeren of stilstaan.';
            case 'F':
                return 'Je rijdt op een weg met extra regels en ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord geeft een verbod of gebod aan.';
            case 'G':
                return 'Je rijdt op een weg met specifieke regels en ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord bepaalt hoe je de weg gebruikt.';
            case 'H':
                return 'Je rijdt de bebouwde kom in of uit en ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord geeft de komgrens aan.';
            case 'J':
                return 'Je rijdt door en ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord waarschuwt voor een risico verderop.';
            case 'K':
                return 'Je nadert een kruispunt en ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord helpt je met route en richting.';
            case 'L':
                return 'Je ziet verkeersbord ' . $code . ' (' . $description . '). Dit bord geeft extra informatie over de weg.';
            default:
                return 'Je ziet verkeersbord ' . $code . ' (' . $description . '). Let op wat dit bord van je vraagt.';
        }
    }

    public function buildChecklist(Sign $sign): array
    {
        $description = Str::lower($sign->description ?? '');
        $letter = $this->categoryLetter($sign);
        $items = [];

        switch ($letter) {
            case 'A':
                $items = [
                    'Lees het getal op het bord.',
                    'Pas je snelheid direct aan.',
                    'Let op einde-borden en onderborden.',
                ];
                break;
            case 'B':
                $items = [
                    'Bepaal wie er voorrang heeft.',
                    'Kijk links en rechts en bevestig de situatie.',
                    'Let op onderborden en uitzonderingen.',
                ];
                break;
            case 'C':
                $items = [
                    'Controleer of het verbod voor jouw voertuig geldt.',
                    'Kies een alternatieve route als dat nodig is.',
                    'Let op onderborden met uitzonderingen.',
                ];
                break;
            case 'D':
                $items = [
                    'Volg de verplichte rijrichting.',
                    'Sorteer op tijd voor.',
                    'Let op rijstroken en borden ernaast.',
                ];
                break;
            case 'E':
                $items = [
                    'Controleer of parkeren of stilstaan is toegestaan.',
                    'Lees tijden en zone-aanduidingen op onderborden.',
                    'Let op wegmarkering en borden in de omgeving.',
                ];
                break;
            case 'F':
                $items = [
                    'Pas je gedrag aan de regel op het bord aan.',
                    'Controleer of het bord een verbod of gebod is.',
                    'Let op onderborden voor uitzonderingen.',
                ];
                break;
            case 'G':
                $items = [
                    'Controleer welke weggebruikers of regels gelden.',
                    'Pas je positie op de weg aan.',
                    'Let op aanvullende borden of markering.',
                ];
                break;
            case 'H':
                $items = [
                    'Controleer of de bebouwde kom begint of eindigt.',
                    'Pas je snelheid aan de komregels aan.',
                    'Let op aanvullende borden voor uitzonderingen.',
                ];
                break;
            case 'J':
                $items = [
                    'Verminder snelheid en wees extra alert.',
                    'Vergroot je volgafstand.',
                    'Bereid je voor op het gevaar dat het bord aangeeft.',
                ];
                break;
            case 'K':
                $items = [
                    'Lees bestemming en richting rustig uit.',
                    'Sorteer tijdig voor.',
                    'Controleer of je de juiste route volgt.',
                ];
                break;
            case 'L':
                $items = [
                    'Lees de informatie volledig uit.',
                    'Bepaal wat dit betekent voor je route of gedrag.',
                    'Let op extra borden in de omgeving.',
                ];
                break;
            default:
                $items = [
                    'Lees het bord volledig.',
                    'Pas je gedrag aan.',
                    'Let op onderborden en uitzonderingen.',
                ];
                break;
        }

        if ($this->contains($description, ['einde'])) {
            $items[] = 'Na dit bord geldt de eerdere regel niet meer.';
        }

        if ($this->contains($description, ['zone'])) {
            $items[] = 'De zone geldt tot het einde-zone bord.';
        }

        return $items;
    }

    public function buildModelAnswers(Sign $sign, array $questions, string $meaning, string $mistake, array $checklist): array
    {
        $answers = [];
        $description = Str::lower($sign->description ?? '');
        $examTip = 'In theorievragen gaat het om het directe effect: wat moet jij nu doen?';
        $actionSummary = $this->buildActionSummary($checklist);

        foreach ($questions as $question) {
            $lower = Str::lower($question);

            if (Str::contains($lower, 'wat betekent')) {
                $answers[] = $meaning . ' ' . $examTip;
                continue;
            }

            if (Str::contains($lower, 'welk gedrag') || Str::contains($lower, 'wat moet je')) {
                $answers[] = $actionSummary . ' Dit is precies waar het CBR op toetst.';
                continue;
            }

            if (Str::contains($lower, 'welke regel stopt')) {
                $answers[] = 'De regel die eerder gold stopt hier. In het examen hoort daarbij: je mag weer rijden volgens de normale regels, tenzij een nieuw bord iets anders zegt.';
                continue;
            }

            if (Str::contains($lower, 'welke uitzonderingen')) {
                $answers[] = 'Onderborden kunnen tijden, voertuigen of uitzonderingen aangeven. Het juiste examenantwoord benoemt altijd die uitzondering.';
                continue;
            }

            if (Str::contains($lower, 'voor welke voertuigen')) {
                $answers[] = 'Het bord geldt voor de voertuigen of weggebruikers die op het bord staan afgebeeld. Kijk in het examen goed naar pictogrammen en onderborden.';
                continue;
            }

            if (Str::contains($lower, 'hoe pas je je snelheid')) {
                $answers[] = 'Snelheid omlaag, afstand vergroten en alert blijven op het genoemde risico. Het examen kiest het antwoord dat veiligheid en anticipatie combineert.';
                continue;
            }

            if (Str::contains($lower, 'mag je hier parkeren')) {
                $answers[] = 'Controleer of parkeren of stilstaan verboden is en kijk naar tijden of zone-borden. Let op: parkeren is langer dan stilstaan.';
                continue;
            }

            if ($this->contains($description, ['einde'])) {
                $answers[] = 'Na dit bord geldt de eerdere regel niet meer. In het examen betekent dit dat je terugvalt op de algemene regels.';
                continue;
            }

            $answers[] = $mistake;
        }

        return $answers;
    }

    public function buildFaq(Sign $sign, string $meaning, string $mistake, array $checklist): array
    {
        $code = $sign->code;
        $faq = [];

        $faq[] = [
            'question' => 'Wat betekent verkeersbord ' . $code . '?',
            'answer' => $meaning,
        ];

        $faq[] = [
            'question' => 'Wat moet je direct doen bij bord ' . $code . '?',
            'answer' => $this->buildActionSummary($checklist),
        ];

        $faq[] = [
            'question' => 'Wat is een veelgemaakte fout bij bord ' . $code . '?',
            'answer' => $mistake,
        ];

        $faq[] = [
            'question' => 'Waar moet je extra op letten bij dit bord?',
            'answer' => 'Let op onderborden, zone-aanduidingen en eventuele einde-borden.',
        ];

        return $faq;
    }

    private function categoryLetter(Sign $sign): string
    {
        return Str::upper(substr($sign->code ?? '', 0, 1));
    }

    private function categoryMeaning(string $letter): string
    {
        $map = [
            'A' => 'Dit bord gaat over snelheid.',
            'B' => 'Dit bord regelt voorrang.',
            'C' => 'Dit bord geeft een geslotenverklaring aan.',
            'D' => 'Dit bord geeft een verplichte rijrichting aan.',
            'E' => 'Dit bord gaat over parkeren en stilstaan.',
            'F' => 'Dit bord geeft een extra verbod of gebod.',
            'G' => 'Dit bord geeft regels over de plaats op de weg.',
            'H' => 'Dit bord hoort bij bebouwde kom regels.',
            'J' => 'Dit bord waarschuwt voor een gevaar.',
            'K' => 'Dit bord geeft richting of route informatie.',
            'L' => 'Dit bord geeft extra informatie.',
        ];

        return $map[$letter] ?? 'Dit bord geeft een verkeersregel of informatie.';
    }

    private function categoryMnemonic(string $letter): string
    {
        $map = [
            'A' => 'A = Aantal km/h.',
            'B' => 'B = Beurt (voorrang).',
            'C' => 'C = Closed (gesloten).',
            'D' => 'D = Direction (richting).',
            'E' => 'E = Even stilstaan?',
            'F' => 'F = Forbidden of gebod.',
            'G' => 'G = Gedrag en plaats.',
            'H' => 'H = Huizen (bebouwde kom).',
            'J' => 'J = Je moet opletten.',
            'K' => 'K = Koers (route).',
            'L' => 'L = Lezen (informatie).',
        ];

        return $map[$letter] ?? 'Let op vorm, kleur en context.';
    }

    private function isEndSign(string $description): bool
    {
        return $this->contains($description, ['einde']);
    }

    private function contains(string $value, array $needles): bool
    {
        $value = Str::lower($value);

        foreach ($needles as $needle) {
            if (Str::contains($value, Str::lower($needle))) {
                return true;
            }
        }

        return false;
    }

    private function buildActionSummary(array $checklist): string
    {
        if (count($checklist) === 0) {
            return 'Pas je gedrag direct aan en volg de regel.';
        }

        $primary = array_slice($checklist, 0, 3);
        $primary = array_map([$this, 'cleanSentence'], $primary);

        return implode('. ', $primary) . '.';
    }

    private function cleanSentence(string $value): string
    {
        return rtrim($value, " \t\n\r\0\x0B.");
    }
}
