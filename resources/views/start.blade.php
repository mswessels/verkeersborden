@extends('app')

@section('content')
    <div class="row">
		<div class="col-xs-12">
			<div class="well clearfix">
				<div class="row">
					<div class="col-md-8">
						<h2 class="mt0">Uitleg</h2>
						<p>Welkom bij de verkeersborden oefening. Onze verkeersborden oefening bestaat uit 20 vragen. Per vraag krijg je 1 verkeersbord en 4 meer keuze antwoorden, klik op het bijbehorende antwoord om door te gaan naar het volgende bord.</p>
						<p>Om te slagen heb je minimaal 15 goede antwoorden nodig, dat betekent dat je dus niet meer dan 5 fout mag hebben. Na de 20 vragen krijg je een gedetailleerd overzicht welke vragen je goed hebt beantwoord en welke niet.</p>
						<b>De verkeersborden oefening is het kort:</b>
						<ul>
							<li>20 vragen</li>
							<li>20 seconden per vraag</li>
							<li>Minimaal 15 vragen goed om te slagen</li>
							<li>Gedetaileerde uitslag met je gegeven antwoorden</li>
							<li><b>De test is helemaal gratis!</b></li>
						</ul>						
						<p>Klik op "Start de oefening" om de oefening te starten, succes!</p>
						<p>
							<a class="btn btn-md btn-default" href="{{ url('/') }}" rel="home" role="button">Naar de homepage</a>
							<a class="btn btn-md btn-success" href="{{ url('/quiz/start') }}" rel="nofollow" role="button">Start de oefening</a>
						</p>
					</div>
					<div class="col-md-3 col-md-push-1 hidden-sm hidden-xs">
						<img src="{{ asset('img/rotonde.png') }}" width="250" width="250" alt="rontonde" title="rotonde" class="img-responsive">
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		
        <article class="col-sm-8 col-lg-6 col-lg-push-1"><br>@include('rectangle')
			<br><br><h1 class="mt0">Verkeersborden Oefenen</h1><br>
			
			
			
			<p>Welkom op de pagina waar u gratis en vrijblijvend <a href="{{ url('/alle-verkeersborden') }}">alle verkeersborden</a> kunt oefenen. Verkeersborden oefenen is belangrijk als u wilt slagen voor uw CBR auto, bromfiets of motor theorie-examen. Het CBR examen wordt vaak onderschat op een manier van, “dat doe ik wel even”. Vaak komen mensen erachter dat zij toch niet alle verkeersborden kennen. Het zijn er ten slotte heel veel. In Nederland hebben wij gevaarsborden, voorrangsborden, verbodsborden, gebodsborden, parkeren- en stiltaanborden en aanwijzingsborden. Al deze borden zijn bedoeld om het verkeer veilig te houden.</p>
			<h2>Veel verkeersborden</h2>
			<p>In Nederland zijn verkeersborden gedefinieerd in het Regelement verkeersregels en verkeerstekens van 1990. Dit regelement deelt de verkeersborden op in verschillende series die van A tot L lopen. Deze series hebben wij eerder al behandeld. De vele soorten verkeersborden en series maken het nog moeilijker voor de kandidaat om zijn CBR examen goed af te ronden. Het is dus erg belangrijk dat alle verkeersborden geoefend worden.</p>
			<h3>Gratis Verkeersborden oefenen</h3>
			<p>Verkeersborden oefenen bent u wel even mee bezig, het zijn er een hoop en alles in één keer erin stampen is erg lastig. Naast de vaste verkeersborden in Nederland zijn er ook nog eens een hoop borden die niet altijd nodig of gewenst, de wegbeheerder in Nederland kan dan door middel van een wisselbord, wegwijzer of een elektronisch matrixbord de verkeerssituatie wijzigen. Op deze manier kan de wegbeheerder dus ingrijpen bij een ongeluk, file of andere onregelmatigheid. Onze website is met veel zorg samengesteld en wordt met dezelfde zorg bijgehouden om op deze manier altijd de juiste verkeersborden online te hebben staan. Dit alles maakt onze website de perfecte plek om uw verkeersborden te oefenen.</p>
			<h4>Slagen voor het CBR theorie examen</h4>
			<p>Verkeersborden oefenen gaat niet vanzelf maar het moet wel gebeuren, u wilt tenslotte slagen, toch? Het CBR examen auto, bromfiets en motor theorie-examen is een flinke opgave. Faalangst, druk en stress kunnen u parten spelen. Het is dus ten strengste aanbevolen om in ieder geval goed voorbereid te zijn, zo voorkomt u een hoop stress. Dit alles maakt de kans op succes natuurlijk alleen maar groter. Wij wensen u vanzelfsprekend heel veel succes met het oefenen van de Nederlandse verkeersborden die u nog vaak tegen zult komen. Succes op het examen!</p>
			<p><a class="btn btn-md btn-success" href="{{ url('/verkeersborden-oefenen') }}" rel="nofollow" role="button">Start de oefening</a></p>
        </article>

        <aside class="col-sm-4 col-lg-3 col-lg-push-2">
			@include('sidebar')
        </aside>
		
    </div><br><br><center><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Standaard ADV -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9270884602953965"
     data-ad-slot="3103351936"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script></center>

@endsection