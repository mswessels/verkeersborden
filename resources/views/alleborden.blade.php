@extends('app')

@section('content')
    <div class="row">
		
        <article class="col-sm-8 col-lg-8">
			<div class="rvv-block rvv-prose">
				<h1 class="mt0">Alle verkeersborden</h1>	
				<p>Welkom op de pagina waar u alle verkeersborden van Nederland op kunt vinden. Hier vindt u de betekenis van alle verkeersborden die in Nederland terug te vinden zijn, met een handige uitleg. Klik op een verkeersbord om de betekenis te bekijken en oefen zo gericht voor het CBR theorie-examen.</p>
			</div>

			@include('rectangle')

			<div class="rvv-block rvv-prose">
				<h2>Verkeersborden oefenen</h2>
				<p>Het oefenen van verkeersborden valt of staat met uzelf. Deze website is een hulpmiddel om gericht te oefenen en sneller te leren. Er zijn vele soorten verkeersborden, in allerlei vormen en kleuren.</p>
			</div>

			<div class="rvv-block rvv-prose">
				<h2>Verkeersborden kennen</h2>
				<p>Een verkeersbord is niet meer weg te denken van de Nederlandse wegen. Verkeersborden begeleiden het verkeer in een veilige stroom. Ook matrixborden die oplichten bij een situatie op de weg vallen daaronder. Ook deze tekens moet u kennen om onaangename verrassingen te voorkomen.</p>
			</div>

			<div class="rvv-block rvv-prose">
				<h2>Conclusie</h2>
				<p>Het is belangrijk om alle verkeersborden te leren. Uw CBR theorie-examen hangt er namelijk vanaf. En zonder deze theorie krijgt u geen rijbewijs. Veel succes met het leren van al die verkeersborden.</p>
			</div>

			<div class="rvv-block">
				<h2>Alle verkeersborden onder elkaar</h2>
				<div class="row mb10">
				@foreach(\App\Sign::get() as $sign)
					<div class="col-sm-6">
						<div class="media">
						  <div class="media-left">
							  <img class="media-object" width="100" height="auto" src="{{ asset('img/borden/'.$sign->image) }}" alt="{{ $sign->description }}" title="{{ $sign->description }}" loading="lazy">				
						  </div>
						  <div class="media-body">
								<p class="text-muted">
									<b>{{ $sign->description }}</b>
									<br/>{{ $sign->code }}
								</p>
						
						  </div>
						</div>
					</div>
				@endforeach
				</div>
			</div>
		</article>
		
		<aside class="col-sm-4 col-lg-4">
			@include('sidebar')
		</aside>
		
    </div>

@endsection
