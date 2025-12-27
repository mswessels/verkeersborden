@extends('app')

@section('content')
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

<section class="rvv-hero rvv-hero--page">
	<div class="rvv-hero__text">
		<p class="rvv-eyebrow">Serie {{ $category_letter }} - {{ $category_name }}</p>
		<h1 class="rvv-title">Verkeersbord {{ $sign->code }}: {{ $sign->description }}</h1>
		<p class="rvv-lead">Betekenis, ezelsbruggetje, veelgemaakte fout en oefenvragen voor bord {{ $sign->code }}.</p>
	</div>
	<div class="rvv-hero__actions">
		<a class="btn btn-primary btn-lg" href="{{ url('/verkeersborden-oefenen') }}" role="button" data-primary-cta>Start de oefening</a>
		<a class="btn btn-ghost btn-lg" href="{{ url('/alle-verkeersborden') }}">Alle verkeersborden</a>
	</div>
</section>

@include('rectangle')

<div class="row">
	<article class="col-sm-8 col-lg-8">
		<div class="rvv-block">
			<div class="row">
				<div class="col-sm-4">
					@include('partials.sign-picture', [
						'image' => $sign->image,
						'size' => 220,
						'class' => 'img-responsive rvv-sign-image',
						'alt' => 'Verkeersbord ' . $sign->code,
						'loading' => 'eager',
					])
				</div>
				<div class="col-sm-8 rvv-prose">
					<h2>Betekenis {{ $sign->code }}</h2>
					<p>{{ $meaning }}</p>
					<div class="rvv-chip-list">
						<span class="rvv-chip">Code: {{ $sign->code }}</span>
						<span class="rvv-chip">Serie {{ $category_letter }}</span>
						<span class="rvv-chip">{{ $category_name }}</span>
					</div>
					<div class="rvv-sign-actions" data-sign-meta data-sign-code="{{ $sign->code }}" data-sign-title="{{ $sign->description }}" data-sign-url="{{ $sign->url }}" data-sign-image="{{ asset('img/borden/'.$sign->image) }}">
						<button class="btn btn-secondary btn-md rvv-learn-toggle" type="button" data-learn-toggle>Markeer als geleerd</button>
						<span class="rvv-learn-status" data-learn-status>Niet gemarkeerd</span>
					</div>
				</div>
			</div>
		</div>

		<div class="rvv-block rvv-prose">
			<h2>Ezelsbruggetje</h2>
			<p>{{ $mnemonic }}</p>
		</div>

		<div class="rvv-block rvv-prose">
			<h2>Veelgemaakte fout</h2>
			<p>{{ $mistake }}</p>
		</div>

		<div class="rvv-block rvv-prose">
			<h2>Praktijkvoorbeeld</h2>
			<p>{{ $scenario }}</p>
		</div>

		<div class="rvv-block rvv-prose">
			<h2>Wat moet je doen?</h2>
			<ul class="rvv-checklist">
				@foreach($checklist as $item)
					<li>{{ $item }}</li>
				@endforeach
			</ul>
		</div>

		<div class="rvv-block rvv-prose">
			<h2>Oefenvragen met modelantwoorden</h2>
			<div class="rvv-qa-list">
				@foreach($questions as $index => $question)
					<details class="rvv-qa">
						<summary>{{ $question }}</summary>
						<p>{{ $answers[$index] ?? '' }}</p>
					</details>
				@endforeach
			</div>
		</div>

		<div class="rvv-block rvv-prose">
			<h2>Veelgestelde vragen</h2>
			<div class="rvv-qa-list">
				@foreach($faq_items as $item)
					<details class="rvv-qa">
						<summary>{{ $item['question'] }}</summary>
						<p>{{ $item['answer'] }}</p>
					</details>
				@endforeach
			</div>
		</div>

		@if(count($related_signs))
		<div class="rvv-block">
			<h2>Vergelijkbare borden</h2>
			<div class="rvv-sign-grid">
				@foreach($related_signs as $related)
					<a class="rvv-sign-card" href="{{ $related->url }}">
						@include('partials.sign-picture', [
							'image' => $related->image,
							'size' => 72,
							'alt' => 'Verkeersbord ' . $related->code,
						])
						<div class="rvv-sign-card__meta">
							<span class="rvv-sign-card__code">{{ $related->code }}</span>
							<span class="rvv-sign-card__title">{{ $related->description }}</span>
						</div>
					</a>
				@endforeach
			</div>
		</div>
		@endif

		<div class="rvv-block">
			<h2>Recent bekeken</h2>
			<div class="rvv-recent-grid" data-recent-list>
				<p class="rvv-recent-empty" data-recent-empty>Je hebt nog geen borden bekeken.</p>
			</div>
		</div>

		@if($prev_sign || $next_sign)
		<div class="rvv-block">
			<h2>Vorige en volgende</h2>
			<div class="rvv-sign-nav">
				@if($prev_sign)
					<a class="rvv-sign-nav__item" href="{{ $prev_sign->url }}">
						<span class="rvv-sign-nav__label">Vorige</span>
						<span class="rvv-sign-nav__title">{{ $prev_sign->code }} - {{ $prev_sign->description }}</span>
					</a>
				@else
					<div class="rvv-sign-nav__item rvv-sign-nav__item--empty">
						<span class="rvv-sign-nav__label">Vorige</span>
						<span class="rvv-sign-nav__title">Geen vorige</span>
					</div>
				@endif

				@if($next_sign)
					<a class="rvv-sign-nav__item" href="{{ $next_sign->url }}">
						<span class="rvv-sign-nav__label">Volgende</span>
						<span class="rvv-sign-nav__title">{{ $next_sign->code }} - {{ $next_sign->description }}</span>
					</a>
				@else
					<div class="rvv-sign-nav__item rvv-sign-nav__item--empty">
						<span class="rvv-sign-nav__label">Volgende</span>
						<span class="rvv-sign-nav__title">Geen volgende</span>
					</div>
				@endif
			</div>
		</div>
		@endif
	</article>

	<aside class="col-sm-4 col-lg-4">
		@include('sidebar')
	</aside>
</div>
@endsection

@section('footer_scripts')
@if($faq_json)
<script type="application/ld+json">{!! $faq_json !!}</script>
@endif
<script src="{{ asset('js/rvv-signs.js') }}"></script>
@endsection

@section('sticky_cta')
<div class="rvv-cta-bar" data-sticky-cta>
	<div class="rvv-cta-bar__inner">
		<a class="btn btn-primary" href="{{ url('/verkeersborden-oefenen') }}">Start de oefening</a>
	</div>
</div>
@endsection
