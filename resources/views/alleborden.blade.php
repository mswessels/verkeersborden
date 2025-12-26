@extends('app')

@section('content')
	
    <div class="row">
		
        <article class="col-sm-8 col-lg-7">@include('rectangle')<br>
			<h1 class="mt0">Alle verkeersborden</h1>	
			
			<p>Welkom op de pagina van onze website waar u alle verkeersborden van Nederland op kunt vinden. Hier vind u de betekenis van alle verkeersborden die in Nederland terug te vinden met een handige uitleg. Het is dan ook erg handig om via deze website de betekenis van de verkeersborden te oefenen voor het CBR auto, bromfiets of motor  theorie-examen. Klik op de afbeelding van een verkeersbord en u krijgt de betekenis van het verkeersbord te zien, op deze manier kunt u uitgebreid oefenen en van alle verkeersborden de betekenis leren. Deze website is ontwikkeld met het oog op zeer strenge kwaliteitsnormen, dit om de kwaliteit van onze website te kunnen waarborgen. Wij vinden het erg belangrijk dat alle verkeersborden altijd up-to-dat zijn met de laatste toevoegingen en nieuwste wet wijzigingen.</p>
			<h2>Verkeersborden oefenen</h2>
			<p>Het oefenen van verkeersborden valt of staat met uzelf. Het is maar net hoe snel u ze leert maar uiteraard ook hoeveel u er zelf voor doet. Deze website moet daar een hulpmiddel bij zijn, uw weg naar een mooi papiertje. Uw rijbewijs is iets waarmee u helemaal los komt, u kunt onafhankelijk rondrijden en veel grotere afstanden afleggen dan zonder. Dat moet toch een motivatie zijn om eens goed te gaan zitten voor al die verkeersborden. Er zijn vele soorten verkeersborden, ze zijn er in allerlei soorten, maten, vormen en kleuren.</p>
			<h2>Verkeersborden kennen</h2>
			<p>Een verkeersbord is niet meer weg te denken van de Nederlandse wegen. Verkeersborden begeleiden namelijk het verkeer in een veilige stroom. Echter zijn het niet allemaal borden die op een vaste plek zijn die het verkeer begeleiden. Zo zijn er bijvoorbeeld matrix borden die oplichten bij een situatie op de weg, deze situatie kan van alles zijn. Ze kunnen gaan branden als gevolg van een ongeluk, maar ook bijvoorbeeld om een file in goede banen te lijden. Ook deze tekens moet u toch allemaal kennen als u niet voor onaangename verassingen wilt komen te staan.</p>
			<h2>Conclusie</h2>
			<p>Het komt er dus op neer dat het uiterst belangrijk is om ze allemaal te leren. Uw CBR theorie-examen hangt er namelijk vanaf. En zonder deze theorie krijgt u ook nooit een rijbewijs. Maar zeg nou toch eerlijk, het is toch fijn als u een verkeersbord ziet staan en u weet gewoon waar hij voor staat? Daarom, heel veel succes met het leren van al die verkeersborden.</p>
			<p><a class="btn btn-md btn-success" href="{{ url('/verkeersborden-oefenen') }}" rel="nofollow" role="button">Start de oefening</a></p>
        
		<br>
		<br>
			<div class="row">
			<div class="col-xs-12">
			<h2>Alle verkeersborden onder elkaar:</h2>
			</div>
			<?php $count = 0; ?>
			@foreach(\App\Sign::get() as $sign)
			<?php $count++ ?>

			@if($count == 1)
			</div>
			<div class="row mb10">
			@endif
			
			<div class="col-sm-6">
			
				<div class="media">
				  <div class="media-left">
					  <img class="media-object" width="100" alt="{{ $sign->description }}" title="{{ $sign->description }}" height="auto" src="{{ asset('img/borden/'.$sign->image) }}" alt="bord">				
				  </div>
				  <div class="media-body">
						<p class="text-muted">
							<b>{{ $sign->description }}</b>
							<br/>{{ $sign->code }}
						</p>
				
				  </div>
				</div>
			
			</div>
			
			<?php if($count == 2): $count = 0; endif; ?>
			
			@endforeach
			</div>
		</article>
		
		<aside class="col-sm-4 col-lg-3 col-lg-push-1">
			@include('sidebar')
		</aside>
		
    </div><br><center><a href="https://ds1.nl/c/?wi=264206&si=532&li=1285845&ws=" rel="nofollow" target="_blank"><img src="https://static-dscn.net/532/1285845/index.php?wi=264206&si=532&li=1285845&ws=" alt="" style="max-width:100%;height:auto;border:none;" /></a><br><br>

@endsection