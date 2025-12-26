<!DOCTYPE html>
<html lang="nl">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<meta name="description" content="{{ isset($meta_description) ? $meta_description : '' }}">

		<title>{{ isset($meta_title) ? $meta_title : ''}} - DeVerkeersborden.nl</title>
		
		<meta property="og:title" content="Gratis Verkeersborden Oefenen" />
		<meta property="og:description" content="Ik heb net verkeersborden geoefend! Nu jij!" />
		<meta name="3b081b73cc1ab73" content="6b552ee54cdacb714c8704dda0128aff" />

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

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>

	<nav class="navbar navbar-default navbar-fixed-top">
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
		</div>
	  </div>
	</nav>
	<div class="container"> 
		<div class="ad text-center hidden-xs">
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
			<hr/>
		</div>
	</div>
	
	<div class="container"> 

		@yield('content')

		<hr/>
		<div class="ad text-center hidden-xs">
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

		<footer class="footer">
		<p>
			&copy; <a rel="home" href="{{ url('/') }}">DeVerkeersborden.nl</a> {{ date('Y') }}
			<a class="pull-right" rel="nofollow" href="{{ url('/links') }}">Links</a>
		</p>
		</footer>
	</div>
	
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

	@yield('footer_scripts')
	
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
