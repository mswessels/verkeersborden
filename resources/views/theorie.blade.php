@extends('app')

@section('content')
    <div class="row">
		
        <article class="col-sm-8 col-lg-8">
			<div class="rvv-block rvv-prose">
				<h1 class="mt0">Theorie examen oefenen <small>dat kan hier gratis</small></h1>
				<p>Wilt u graag uw theorie examen oefenen voor uw CBR auto, bromfiets of motor theorie-examen? Dan bent u op deze pagina aan het juiste adres. Wij houden de verkeersborden en regels actueel zodat u met vertrouwen oefent.</p>
			</div>

			@include('rectangle')

			<div class="rvv-block rvv-prose">
				<h2>Veel verkeersborden en regels</h2>
				<p>Om rijbevoegd te worden in Nederland dient u in het bezit te zijn van een geldig Nederlands rijbewijs. Het theorie-examen is een horde die velen nemen en falen, vaak doordat men de hoeveelheid borden onderschat.</p>
			</div>

			<div class="rvv-block rvv-prose">
				<h2>Gratis theorie oefenen</h2>
				<p>Gelukkig kunt u bij ons gratis en vrijblijvend uw theorie- en bordenkennis oefenen. Het CBR examen kunt u hier in alle rust oefenen, zodat u precies weet waar u aan toe bent.</p>
			</div>

			<div class="rvv-block rvv-prose">
				<h3>Auto theorie geldigheid</h3>
				<p>Het CBR auto, bromfiets en motor theorie-examen is na het behalen anderhalf jaar geldig. Zorg daarom dat u tijdig uw theorie-examen haalt, op die manier kunt u veilig en goed voorbereid de weg op.</p>
				<p><a class="btn btn-secondary btn-md" href="{{ url('/verkeersborden-oefenen') }}" rel="nofollow" role="button">Start de oefening</a></p>
			</div>

			<div class="rvv-block">
				<h3>Zo gaat het bij het theorie examen</h3>
				<div class="embed-responsive embed-responsive-16by9">
				  <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/ojccsfrIxoU" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
	   </article>
		
		<aside class="col-sm-4 col-lg-4">
			@include('sidebar')
		</aside>
		
    </div>

@endsection
