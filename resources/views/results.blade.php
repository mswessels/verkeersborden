@extends('app')

@section('content')
	<div class="row">
		<div class="hidden-xs hidden-sm col-md-3">
			@if( $passed )
				<img class="img-responsive img-rounded" alt="geslaagd" src="{{ asset('img/passed.jpg') }}">
			@else 
				<img class="img-responsive img-rounded" alt="gezakt" src="{{ asset('img/failed.jpg') }}">
			@endif
			<br>
			<a class="btn btn-default btn-lg btn-block" href="{{ url('/quiz/start') }}">Herstart de oefening</a>
		</div>
		<div class="col-sm-8 col-md-6">
			@if( $passed )
			
				<h1 class="text-success mt0"><b>Gefeliciteerd</b>, je bent geslaagd!</h1>
				<p>Hallo {{ Auth::user()->name}}, je bent geslaagd voor de verkeersborden oefening!<br/>
Vraag hier meteen je theorie examen aan. <a target="_blank" href="https://www.cbr.nl/?utm_source=deverkeersborden.nl&utm_medium=test_geslaagd&utm_campaign=deverkeersborden.nl">Naar de website van het CBR</a><br/><br><br>
			
			@else 
				
				<h1 class="text-danger mt0"><b>Helaas</b>, je bent gezakt...</h1>
				<p>Hallo {{ Auth::user()->name}}, je bent gezakt voor de verkeersborden oefening!<br/>
			
			@endif
			
			<b>Je hebt {{ $results->right }} van de {{ $results->total }} vragen goed beantwoord</b>, er waren 15 goede antwoorden nodig om te slagen voor deze oefening.</p>
			
			<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ URL::to('/') }}&description=Ik heb net mijn kennis over verkeersborden getest! Bekijk de handige website en doe ook een gratis test op deverkeersborden.nl" title="Deel je score op Facebook" class="btn btn-social btn-facebook mb10" onclick="ga('send', 'event', 'SocialShare', 'Facebook');"><i class="fa fa-facebook"></i> Deel je score op Facebook</a>
			<a target="_blank" href="https://twitter.com/intent/tweet?text=Ik heb {{ $results->right }} van de 20 %23verkeersborden goed beantwoord! Probeer het ook! @Verkeersborden_ Klik hier: &url={{ URL::to('/') }}&amp;source=webclient" title="Deel je score op Twitter" class="btn btn-social btn-twitter mb10" onclick="ga('send', 'event', 'SocialShare', 'Twitter');"><i class="fa fa-twitter"></i> Deel je score op Twitter</a>
			<br>
			<p>Op deze pagina zie je alle borden nog eens onder elkaar samen met de door jou gegeven antwoorden en de goede antwoorden. 
			In de rechter kolom zie je je test geschiedenis zodat je gelijk kunt zien of je beter wordt.</p>
			
			<h3>Je gegeven antwoorden:</h3>
			<hr/>
			
			@foreach($questions as $question => $answer)

			<?php $correct = \App\Sign::find($question); ?>
			<?php $fout = empty($answer) ? '' : \App\Sign::find($answer); ?>
			
			<div class="media">
			  <div class="media-left">
				  <img class="media-object" width="100" height="auto" src="{{ asset('img/borden/'.$correct->image) }}" alt="bord" loading="lazy">				
			  </div>
			  <div class="media-body">
				@if($question == $answer)
					<h4 class="media-heading text-success"><b>Goed</b> beantwoord</h4>
					<p class="text-muted"><b>Uw antwoord was: {{ $correct->description }}</b><p>
				@else
					<h4 class="media-heading text-danger"><b>Fout</b> beantwoord</h4>
					<p class="text-muted"><b>Uw antwoord was:</b> {{ empty($fout) ? '' : $fout->description }}<p>
					<p class="text-success"><b>Goede antwoord:</b> {{ empty($correct->description_short) ? $correct->description : $correct->description_short }}<p>
				@endif
			  </div>
			</div>
			@endforeach
			
			<br/>
			<br/>
			<br/>
		</div>
		<div class="col-sm-4 col-md-3">
			<div class="panel panel-default">
			  <!-- Default panel contents -->
			  <div class="panel-heading"><h3 class="panel-title">Mijn eerder behaalde scores</h3></div>

			  <ul class="list-group">
				
				@foreach($myresults as $myresult)
				
				<li class="list-group-item">
					<span style="display:block" class="clearfix">
						<small class="pull-right text-muted" style="display:inline-block;">{{ $myresult->created_at->format('H:i d-m-Y') }}</small>
						<span class="pull-left {{ $myresult->right >= 15 ? 'text-success' : 'text-danger' }}" style="display:inline-block;line-height:14px;margin-bottom:8px;">{{ $myresult->right >= 15 ? 'Geslaagd' : 'Gezakt' }}</span>
					</span>
					
					<div class="progress" style="margin-bottom:0px">
					  <div class="progress-bar progress-bar-success" style="width:{{ 100 / 20 * $myresult->right }}%">{{ $myresult->right }}</div>
					  <div class="progress-bar progress-bar-danger" style="width:{{ 100 / 20 * $myresult->wrong }}%">{{ $myresult->wrong }}</div>
					</div>
				</li>
				
				@endforeach
				
			  </ul>
			</div>
			
			@if( count( $otherresults ) )
			<div class="panel panel-default hidden-xs hidden-sm">
			  <div class="panel-heading"><h3 class="panel-title">Andere kandidaten</h3></div>

			  <ul class="list-group">
			  
				@foreach($otherresults as $otherresult)
				
				<li class="list-group-item">
					<span style="display:block" class="clearfix">
						<small class="pull-right text-muted" style="display:inline-block;">{{ $otherresult->created_at->format('d-m-Y') }}</small>
						<span class="pull-left {{ $otherresult->right >= 15 ? 'text-success' : 'text-danger' }}" style="display:inline-block;line-height:14px;margin-bottom:8px;">{{ explode(' ',$otherresult->user->name)[0] }} is {{ $otherresult->right >= 15 ? 'geslaagd' : 'gezakt' }}</span>
					</span>
					
					<div class="progress" style="margin-bottom:0px">
					  <div class="progress-bar progress-bar-success" style="width:{{ 100 / 20 * $otherresult->right }}%">{{ $otherresult->right }}</div>
					  <div class="progress-bar progress-bar-danger" style="width:{{ 100 / 20 * $otherresult->wrong }}%">{{ $otherresult->wrong }}</div>
					</div>
				</li>
				
				@endforeach
				
			  </ul>
			</div>
			@endif
			<a class="btn btn-default btn-lg btn-block" href="{{ url('/quiz/start') }}">Herstart de oefening</a>
		</div>
	</div>
@endsection
