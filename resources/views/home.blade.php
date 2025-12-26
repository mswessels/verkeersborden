@extends('app')

@section('content')

<div class="jumbotron">
	<h2>Verkeersborden Oefenen</h2>
	<p>De beste website waar het mogelijk is om <b>GRATIS</b> verkeersborden te oefenen.</p>
	<p><a class="btn btn-lg btn-success" href="{{ url('/verkeersborden-oefenen') }}" role="button">Start de oefening</a></p>
</div>

<div class="row">

	<article class="col-sm-8 col-lg-6 col-lg-push-1">@include('rectangle')<br>
		<h1 class="mt0">Welkom op DeVerkeersborden.nl!</h1>
		
		
		
		<p>Welkom op de verkeersbord trainingswebsite van Nederland. Op deze website kunt u gratis en vrijblijvend <a href="{{ URL::to('verkeersborden-oefenen') }}">verkeersborden oefenen</a>. Verkeersborden die u uit uw hoofd moet leren als u ooit uw rijbewijs wilt halen. Op deze website kunt u de betekenis van alle verkeersborden vinden, die te vinden zijn op de Nederlandse wegen. Het is erg handig om gratis via onze website verkeersborden te oefenen zodat u goed voorbereid op weg gaat naar uw theorie examen en dus uw rijbewijs.</p>
		<p><a href="{{ URL::to('alle-verkeersborden') }}">Verkeersborden</a> zijn er in allerlei soorten, maten en kleuren. Het is dus nogal wat om ze allemaal uit het hoofd te moeten leren, om zodoende goed voorbereid uw CBR theorie examen in te gaan. In Nederland zijn verkeersborden opgedeeld in verschillende series, hieronder treft u een overzicht van alle series en zijn betekenis.</p>
		<h2>Verkeersbord categorieën</h2>
		<p><b>Serie A	:</b> Snelheid<br/>
		<b>Serie B	:</b> Voorrang<br/>
		<b>Serie C :</b> Geslotenverklaring<br/>
		<b>Serie D	:</b> Rijrichting<br/>
		<b>Serie E	:</b> Parkeren en stiltaan<br/>
		<b>Serie F	:</b> Overige geboden en verboden<br/>
		<b>Serie G	:</b> Verkeersregels<br/>
		<b>Serie H	:</b> Bebouwde kom<br/>
		<b>Serie J	:</b> Waarschuwing<br/>
		<b>Serie K	:</b> Bewegwijzering<br/>
		<b>Serie L	:</b> Informatie</p>
		<p>De verkeersborden zijn dus opgedeeld in een flink aantal series en categorieën, en dan bestaan er in Nederland ook nog allerlei verkeersborden voor op het water, de spoorwegen en luchthavens. Op deze website behandelen wij echter alleen de verkeersborden voor de automobilisten, bromfietsers en fietsers, het algemene verkeer dus.</p>
		<h3>Verkeersborden oefenen</h3>
		<p>Nederland is dus een land vol borden, maar hoeveel en hoe goed kent u ze? Doe de test op onze website en kom erachter. Daarnaast komt u er ook achter welke borden u niet kent. Op onze mooie website krijgt u dan ook nog eens de kans om deze kennis bij te schaven. Dit is dus die ideale website om verkeersborden te oefenen voor het CBR auto, bromfiets of motor theorie-examen. Naast het oefenen voor deze examens kunt u ze natuurlijk ook gewoon leren omdat u daar behoefte aan heeft of omdat u het leuk vind. Wij wensen u in ieder geval veel succes met het oefenen op onze website!</p>
		<p><a class="btn btn-md btn-success" href="{{ url('/verkeersborden-oefenen') }}" role="button">Klik hier om de quiz te starten</a></p>
	</article>

	<aside class="col-sm-4 col-lg-3 col-lg-push-2">
		@include('sidebar')		
	</aside>

</div><br><br>
@endsection


