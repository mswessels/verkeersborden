@extends('app')

@section('content')
@if(isset($breadcrumbs))
<nav class="rvv-breadcrumb" aria-label="Breadcrumb">
	<ol>
		@foreach($breadcrumbs as $crumb)
			<li>
				@if($crumb['url'])
					<a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
				@else
					<span aria-current="page">{{ $crumb['label'] }}</span>
				@endif
			</li>
		@endforeach
	</ol>
</nav>
@endif

<section class="rvv-hero rvv-hero--page">
	<div class="rvv-hero__text">
		<p class="rvv-eyebrow">Serie {{ $category->letter }} - {{ $category->name }}</p>
		<h1 class="rvv-title">{{ $series_info['title'] }}</h1>
		<p class="rvv-lead">{{ $series_info['intro'] }}</p>
	</div>
	<div class="rvv-hero__actions">
		<a class="btn btn-primary btn-lg" href="{{ url('/verkeersborden-oefenen') }}">Start de oefening</a>
		<a class="btn btn-ghost btn-lg" href="{{ url('/alle-verkeersborden') }}">Alle verkeersborden</a>
	</div>
</section>

@include('rectangle')

<div class="row">
	<article class="col-sm-8 col-lg-8">
		<div class="rvv-block rvv-prose">
			<h2>Over serie {{ $category->letter }}</h2>
			<p>{{ $series_info['intro'] }}</p>
		</div>

		<div class="rvv-block rvv-prose">
			<h2>Oefentips voor serie {{ $category->letter }}</h2>
			<ul class="rvv-checklist">
				@foreach($series_info['tips'] as $tip)
					<li>{{ $tip }}</li>
				@endforeach
			</ul>
		</div>

		<div class="rvv-block">
			<h2>Alle verkeersborden in serie {{ $category->letter }}</h2>
			<div class="row mb10 rvv-sign-list">
				@foreach($signs as $sign)
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

		@if(isset($all_categories) && $all_categories->count())
		<div class="rvv-block rvv-prose">
			<h2>Andere series</h2>
			<p>Bekijk ook de andere series verkeersborden voor een compleet overzicht.</p>
			<div class="rvv-chip-list">
				@foreach($all_categories as $other)
					<a class="rvv-chip rvv-chip--link" href="{{ url('/verkeersborden/serie-'.strtolower($other->letter)) }}">Serie {{ $other->letter }}</a>
				@endforeach
			</div>
		</div>
		@endif
	</article>

	<aside class="col-sm-4 col-lg-4">
		@include('sidebar')
	</aside>
</div>
@endsection
