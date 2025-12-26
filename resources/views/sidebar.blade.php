<a class="btn btn-default btn-md btn-block" href="{{ url('/verkeersborden-oefenen') }}">Start de oefening</a>
<br/>
<div class="cta">
<h4><i class="fa fa-child"></i> Helemaal gratis </h4>
<h4><i class="fa fa-graduation-cap"></i> Al {{ \App\Result::count() }} tests gedaan</h4>
<h4><i class="fa fa-list-ol"></i> Duidelijke resultaten</h4>
<h4><i class="fa fa-bar-chart"></i> Bekijk je vooruitgang</h4>
<h4><i class="fa fa-graduation-cap"></i><a href="https://dt51.net/c/?wi=264206&amp;si=2320&amp;li=1523860&amp;ws=" rel="nofollow" target="_blank"> Theorie.nl</a></h4>	
<h4><i class="fa fa-car"></i> Haal je rijbewijs sneller</h4>	
</div>
<a class="btn btn-default btn-md btn-block" href="{{ url('/verkeersborden-oefenen') }}">Start de oefening</a>

<br/>

<div class="panel panel-default">
  <div class="panel-heading"><h3 class="panel-title">Laatst behaalde resultaten</h3></div>

  <ul class="list-group">
  
	@foreach(\App\Result::with('user')->orderBy('created_at','DESC')->take(5)->get() as $otherresult)
	
	<li class="list-group-item">
		<span style="display:block" class="clearfix">
			<small class="pull-right text-muted" style="display:inline-block;">{{ $otherresult->created_at->format('d-m-Y') }}</small>
			<span class="pull-left {{{ $otherresult->right >= 15 ? 'text-success' : 'text-danger' }}}" style="display:inline-block;line-height:14px;margin-bottom:8px;">{{ explode(' ',$otherresult->user->name)[0] }} is {{{ $otherresult->right >= 15 ? 'geslaagd' : 'gezakt' }}}</span>
		</span>
		
		<div class="progress" style="margin-bottom:0px">
		  <div class="progress-bar progress-bar-success" style="width:{{ 100 / 20 * $otherresult->right }}%">{{ $otherresult->right }}</div>
		  <div class="progress-bar progress-bar-danger" style="width:{{ 100 / 20 * $otherresult->wrong }}%">{{ $otherresult->wrong }}</div>
		</div>
	</li>
	
	@endforeach
	
	<div class="panel-body">
		<a class="btn btn-default btn-md btn-block" href="{{ url('/verkeersborden-oefenen') }}">Start de oefening</a>
	</div>
  </ul></div><center><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
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
  <br><br>
<iframe style="overflow: hidden; border: none" src="https://html.dt51.net/2320/1520830/index.php?wi=264206&amp;si=2320&amp;ws=sidebar" width="250" height="250" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe></center>

