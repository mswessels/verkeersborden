@extends('app')

@section('content')
<section class="rvv-hero rvv-hero--page">
	<div class="rvv-hero__text">
		<p class="rvv-eyebrow">Oefenmodus</p>
		<h1 class="rvv-title">Verkeersborden oefenen</h1>
		<p class="rvv-lead">20 vragen, 20 seconden per vraag en direct inzicht in je score. Start wanneer je wilt.</p>
	</div>
	<div class="rvv-hero__actions">
		<a class="btn btn-primary btn-lg" href="{{ url('/quiz/start') }}" rel="nofollow" role="button" data-primary-cta>Start de oefening</a>
		<a class="btn btn-ghost btn-lg" href="{{ url('/') }}" rel="home" role="button">Naar de homepage</a>
	</div>
</section>

@include('rectangle')

<div class="row">
	<article class="col-sm-8 col-lg-8">
		<div class="rvv-block">
			<div class="row">
				<div class="col-md-8 rvv-prose">
					<h2>Uitleg</h2>
					<p>Welkom bij de verkeersborden oefening. Onze oefening bestaat uit 20 vragen. Per vraag krijg je 1 verkeersbord en 4 meerkeuze antwoorden.</p>
					<p>Om te slagen heb je minimaal 15 goede antwoorden nodig. Na de 20 vragen krijg je een gedetailleerd overzicht met je antwoorden.</p>
					<ul class="rvv-checklist">
						<li>20 vragen</li>
						<li>20 seconden per vraag</li>
						<li>Minimaal 15 vragen goed om te slagen</li>
						<li>Gedetailleerde uitslag met je gegeven antwoorden</li>
						<li>De test is helemaal gratis</li>
					</ul>
				</div>
				<div class="col-md-4 hidden-sm hidden-xs">
					<img src="{{ asset('img/rotonde.png') }}" width="250" height="250" alt="rotonde" title="rotonde" class="img-responsive">
				</div>
			</div>
		</div>

		<div class="rvv-block rvv-prose">
			<h2>Veel verkeersborden</h2>
			<p>In Nederland zijn verkeersborden gedefinieerd in het Reglement verkeersregels en verkeerstekens van 1990. Dit reglement deelt de verkeersborden op in verschillende series die van A tot L lopen. Het is dus erg belangrijk dat alle verkeersborden geoefend worden.</p>
		</div>

		<div class="rvv-block rvv-prose">
			<h2>Gratis verkeersborden oefenen</h2>
			<p>Verkeersborden oefenen kost tijd; het zijn er veel. Naast de vaste verkeersborden kan de wegbeheerder ook wisselborden, wegwijzers of elektronische matrixborden inzetten om situaties te wijzigen. Onze website is met zorg samengesteld en wordt actueel gehouden.</p>
		</div>

		<div class="rvv-block rvv-prose">
			<h2>Slagen voor het CBR theorie examen</h2>
			<p>Het CBR examen auto, bromfiets en motor theorie-examen is een flinke opgave. Faalangst, druk en stress kunnen meespelen. Goed voorbereid zijn voorkomt stress en vergroot de kans op succes. Veel succes met het oefenen.</p>
		</div>
	</article>

	<aside class="col-sm-4 col-lg-4">
		@include('sidebar')
	</aside>
</div>

@endsection

@section('sticky_cta')
<div class="rvv-cta-bar" data-sticky-cta>
	<div class="rvv-cta-bar__inner">
		<a class="btn btn-primary" href="{{ url('/quiz/start') }}">Start de oefening</a>
	</div>
</div>
@endsection
