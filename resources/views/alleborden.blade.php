@extends('app')

@section('content')
    <div class="row">
		
        <article class="col-sm-8 col-lg-8">
			<div class="rvv-block rvv-prose">
				<h1 class="mt0">Alle verkeersborden</h1>	
				<p>Welkom op de pagina waar u alle verkeersborden van Nederland op kunt vinden. Hier vindt u de betekenis van alle verkeersborden die in Nederland terug te vinden zijn, met een handige uitleg. Klik op een verkeersbord om de betekenis te bekijken en oefen zo gericht voor het CBR theorie-examen.</p>
			</div>

			@include('rectangle')

			<div class="rvv-block">
				<form class="rvv-search" method="GET" action="{{ url('/alle-verkeersborden') }}" role="search">
					<label class="rvv-search__label" for="sign-search">Zoek een verkeersbord</label>
					<div class="rvv-search__row">
						<input id="sign-search" class="form-control" type="text" name="q" value="{{ $search_query ?? '' }}" placeholder="Zoek op code of betekenis">
						<button class="btn btn-primary" type="submit">Zoek</button>
						@if(!empty($search_query))
							<a class="btn btn-ghost" href="{{ url('/alle-verkeersborden') }}">Reset</a>
						@endif
					</div>
				</form>
			</div>

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

			@if($categories->count())
				<div class="rvv-block rvv-prose">
					<h2>Alle verkeersborden per serie</h2>
					<p>Kies een serie om snel naar het juiste hoofdstuk te springen. Klik op een bord voor de complete uitleg en oefenvragen.</p>
					<div class="rvv-chip-list">
						@foreach($categories as $category)
							<a class="rvv-chip rvv-chip--link" href="{{ url('/verkeersborden/serie-'.strtolower($category->letter)) }}">Serie {{ $category->letter }}</a>
						@endforeach
					</div>
					@if(!empty($search_query))
						@php
							$resultCount = $categories->sum(function ($category) {
								return $category->signs->count();
							});
						@endphp
						<p><strong>{{ $resultCount }}</strong> resultaten voor "{{ $search_query }}".</p>
					@endif
				</div>

				@foreach($categories as $category)
					<div class="rvv-block rvv-prose" id="serie-{{ strtolower($category->letter) }}">
						<h2><a class="rvv-sign-link" href="{{ url('/verkeersborden/serie-'.strtolower($category->letter)) }}">Serie {{ $category->letter }} - {{ $category->name }}</a></h2>
						<p>Bekijk alle borden uit serie {{ $category->letter }} en klik op een bord voor betekenis, ezelsbruggetje en oefenvragen.</p>
					</div>
					<div class="rvv-block">
						<div class="row mb10 rvv-sign-list">
						@foreach($category->signs as $sign)
							<div class="col-sm-6">
								<div class="media">
								  <div class="media-left">
									  <a class="rvv-sign-link" href="{{ $sign->url }}">
										@include('partials.sign-picture', [
											'image' => $sign->image,
											'size' => 80,
											'class' => 'media-object rvv-sign-thumb',
											'alt' => $sign->description,
											'title' => $sign->description,
										])
									  </a>
								  </div>
								  <div class="media-body">
										<p class="text-muted">
											<a class="rvv-sign-link" href="{{ $sign->url }}"><b>{{ $sign->description }}</b></a>
											<br/>{{ $sign->code }}
										</p>
								  </div>
								</div>
							</div>
						@endforeach
						</div>
					</div>
				@endforeach
			@else
				@if(!empty($search_query))
					<div class="rvv-block rvv-prose">
						<h2>Geen resultaten</h2>
						<p>Geen verkeersborden gevonden voor "{{ $search_query }}". Probeer een andere zoekterm.</p>
					</div>
				@else
					<div class="rvv-block">
						<h2>Alle verkeersborden onder elkaar</h2>
						<div class="row mb10 rvv-sign-list">
						@foreach(\App\Sign::orderBy('code')->get() as $sign)
							<div class="col-sm-6">
								<div class="media">
								  <div class="media-left">
									  <a class="rvv-sign-link" href="{{ $sign->url }}">
										@include('partials.sign-picture', [
											'image' => $sign->image,
											'size' => 80,
											'class' => 'media-object rvv-sign-thumb',
											'alt' => $sign->description,
											'title' => $sign->description,
										])
									  </a>
								  </div>
								  <div class="media-body">
										<p class="text-muted">
											<a class="rvv-sign-link" href="{{ $sign->url }}"><b>{{ $sign->description }}</b></a>
											<br/>{{ $sign->code }}
										</p>
								  </div>
								</div>
							</div>
						@endforeach
						</div>
					</div>
				@endif
			@endif
		</article>
		
		<aside class="col-sm-4 col-lg-4">
			@include('sidebar')
		</aside>
		
    </div>

@endsection
