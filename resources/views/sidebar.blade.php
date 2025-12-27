<div class="rvv-block rvv-sidecard">
  <h3 class="rvv-sidecard__title">Start oefenen</h3>
  <ul class="rvv-checklist">
	<li>Helemaal gratis</li>
	<li>Al {{ \App\Result::count() }} tests gedaan</li>
	<li>Duidelijke resultaten</li>
	<li>Bekijk je vooruitgang</li>
	<li><a href="https://dt51.net/c/?wi=264206&amp;si=2320&amp;li=1523860&amp;ws=" rel="nofollow" target="_blank">Theorie.nl</a></li>
	<li>Haal je rijbewijs sneller</li>
  </ul>
  <a class="btn btn-secondary btn-md btn-block" href="{{ url('/verkeersborden-oefenen') }}">Start de oefening</a>
</div>

<div class="panel panel-default rvv-panel">
  <div class="panel-heading"><h3 class="panel-title">Laatst behaalde resultaten</h3></div>

  <ul class="list-group">
  
	@foreach(\App\Result::with('user')->orderBy('created_at','DESC')->take(5)->get() as $otherresult)
	
	<li class="list-group-item">
		<span style="display:block" class="clearfix">
			<small class="float-end text-muted" style="display:inline-block;">{{ $otherresult->created_at->format('d-m-Y') }}</small>
			<span class="float-start {{ $otherresult->right >= 15 ? 'text-success' : 'text-danger' }}" style="display:inline-block;line-height:14px;margin-bottom:8px;">{{ explode(' ',$otherresult->user->name)[0] }} is {{ $otherresult->right >= 15 ? 'geslaagd' : 'gezakt' }}</span>
		</span>
		
		<div class="progress" style="margin-bottom:0px">
		  <div class="progress-bar progress-bar-success" style="width:{{ 100 / 20 * $otherresult->right }}%">{{ $otherresult->right }}</div>
		  <div class="progress-bar progress-bar-danger" style="width:{{ 100 / 20 * $otherresult->wrong }}%">{{ $otherresult->wrong }}</div>
		</div>
	</li>
	
	@endforeach
	
  </ul>
</div>

<div class="rvv-ad rvv-ad--square rvv-ad--sidebar">
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
  <iframe title="Advertentie" style="overflow: hidden; border: none" src="https://html.dt51.net/2320/1520830/index.php?wi=264206&amp;si=2320&amp;ws=sidebar" width="250" height="250" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>
</div>
