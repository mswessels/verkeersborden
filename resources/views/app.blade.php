<!DOCTYPE html>
<html lang="nl">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

	@php
		$siteName = 'DeVerkeersborden.nl';
		$siteUrl = rtrim(config('app.url') ?: url('/'), '/');
		$metaTitle = $meta_title ?? $siteName;
		$metaDescription = $meta_description ?? 'Oefen verkeersborden met een gratis quiz en leer betekenissen, regels en ezelsbruggetjes voor je theorie-examen.';
		$canonicalUrl = $canonical ?? url()->current();
		$metaImage = $meta_image ?? asset('img/automobile-2679744_640.jpg');
		$metaType = $meta_type ?? 'website';
	@endphp

	<meta name="description" content="{{ $metaDescription }}">
	<link rel="canonical" href="{{ $canonicalUrl }}">

	<title>{{ $metaTitle }}{{ strpos($metaTitle, $siteName) === false ? ' - ' . $siteName : '' }}</title>

	<meta property="og:locale" content="nl_NL">
	<meta property="og:site_name" content="{{ $siteName }}">
	<meta property="og:title" content="{{ $metaTitle }}">
	<meta property="og:description" content="{{ $metaDescription }}">
	<meta property="og:type" content="{{ $metaType }}">
	<meta property="og:url" content="{{ $canonicalUrl }}">
	<meta property="og:image" content="{{ $metaImage }}">

	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="{{ $metaTitle }}">
	<meta name="twitter:description" content="{{ $metaDescription }}">
	<meta name="twitter:image" content="{{ $metaImage }}">

	@php
		$structuredData = [];
		$structuredData[] = [
			'@context' => 'https://schema.org',
			'@type' => 'Organization',
			'name' => $siteName,
			'url' => $siteUrl,
			'logo' => asset('img/kaart.png'),
		];
		$structuredData[] = [
			'@context' => 'https://schema.org',
			'@type' => 'WebSite',
			'name' => $siteName,
			'url' => $siteUrl,
			'potentialAction' => [
				'@type' => 'SearchAction',
				'target' => $siteUrl . '/alle-verkeersborden?q={search_term_string}',
				'query-input' => 'required name=search_term_string',
			],
		];

		if (isset($breadcrumbs) && is_array($breadcrumbs) && count($breadcrumbs) > 0) {
			$listItems = [];
			$position = 1;
			foreach ($breadcrumbs as $crumb) {
				$label = $crumb['label'] ?? null;
				if (!$label) {
					continue;
				}
				$listItems[] = [
					'@type' => 'ListItem',
					'position' => $position++,
					'name' => $label,
					'item' => $crumb['url'] ?? $canonicalUrl,
				];
			}

			if ($listItems) {
				$structuredData[] = [
					'@context' => 'https://schema.org',
					'@type' => 'BreadcrumbList',
					'itemListElement' => $listItems,
				];
			}
		}
	@endphp

	@foreach($structuredData as $schema)
		<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
	@endforeach
	<meta name="3b081b73cc1ab73" content="6b552ee54cdacb714c8704dda0128aff" />

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-9270884602953965",
          enable_page_level_ads: true
     });
</script>
		
	<link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/css/bootstrap.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
	@vite('resources/js/app.js')
	<link href="{{ asset('css/rvv-2026.css') }}" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body class="rvv-body">

	<nav class="navbar navbar-default rvv-topbar">
	  <div class="container">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="{{ url('/') }}"><i class="fa fa-car"></i> DeVerkeersborden.nl</a>
		</div>

		<div class="collapse navbar-collapse" id="navbar">
		  <ul class="nav navbar-nav">
			<li><a href="{{ url('verkeersborden-oefenen') }}">Verkeersborden Oefenen</a></li>        
			<li><a href="{{ url('alle-verkeersborden') }}">Alle Verkeersborden</a></li>
			<li><a href="{{ url('theorie-examen-oefenen') }}">Theorie examen</a></li>
		  </ul>
		  <ul class="nav navbar-nav navbar-right rvv-nav-actions">
			<li class="rvv-nav-toggle">
			  <button class="theme-toggle" type="button" data-theme-toggle aria-pressed="false">
				<span class="theme-toggle__orb" aria-hidden="true"></span>
				<span class="theme-toggle__label">Thema</span>
				<span class="theme-toggle__state" data-theme-label>licht</span>
			  </button>
			</li>
		  </ul>
		</div>
	  </div>
	</nav>
	<div class="rvv-app">
	  <main class="rvv-main">
		<div class="container rvv-section rvv-section--content"> 

			@yield('content')

			<div class="rvv-ad rvv-ad--leaderboard hidden-xs">
				<p class="rvv-ad__label">Advertentie</p>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Standaard ADV -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9270884602953965"
     data-ad-slot="3103351936"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
			</div>

			<footer class="footer rvv-footer">
			<p>
				&copy; <a rel="home" href="{{ url('/') }}">DeVerkeersborden.nl</a> {{ date('Y') }}
				<a class="pull-right" rel="nofollow" href="{{ url('/links') }}">Links</a>
			</p>
			</footer>
		</div>
	  </main>
	</div>
	
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

	@yield('footer_scripts')

	@yield('sticky_cta')
	<script src="{{ asset('js/rvv-2026.js') }}"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-85579584-1', 'auto');
  ga('send', 'pageview');

</script>
<!-- Begin TradeTracker SuperTag Code -->
<script type="text/javascript">

    var _TradeTrackerTagOptions = {
        t: 'a',
        s: '260225',
        chk: 'b39a888679b0173d2d0ba63efe53eb87',
        overrideOptions: {}
    };

    (function() {var tt = document.createElement('script'), s = document.getElementsByTagName('script')[0]; tt.setAttribute('type', 'text/javascript'); tt.setAttribute('src', (document.location.protocol == 'https:' ? 'https' : 'http') + '://tm.tradetracker.net/tag?t=' + _TradeTrackerTagOptions.t + '&amp;s=' + _TradeTrackerTagOptions.s + '&amp;chk=' + _TradeTrackerTagOptions.chk); s.parentNode.insertBefore(tt, s);})();
</script>
<!-- End TradeTracker SuperTag Code -->
</body>
</html>
