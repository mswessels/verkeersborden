@extends('app')

@section('content')
					
	<div class="omb_login">
		<h1 class="omb_authTitle">Je bent klaar met de oefening!</h1>
		<h3 class="omb_authTitle">We zijn je antwoorden aan het nakijken...</h3>
		<h4 class="omb_authTitle text-muted">Login om je uitslag te bekijken, dit is nodig om je score op te slaan.<br/>Geen zorgen, wij houden ook niet van spam.</h4>
		<div class="row col-md-offset-3 omb_socialButtons">
		
			<div class="col-xs-6 col-md-4">
				<a href="{{ url('/auth/facebook') }}" class="btn btn-lg btn-block omb_btn-facebook" onclick="ga('send', 'event', 'register', 'Facebook');">
					<i class="fa fa-facebook visible-xs"></i>
					<span class="hidden-xs">Login met Facebook</span>
				</a>
			</div>
			<div class="col-xs-6 col-md-4">
				<a href="{{ url('/auth/twitter') }}" class="btn btn-lg btn-block omb_btn-twitter" onclick="ga('send', 'event', 'register', 'Twitter');">
					<i class="fa fa-twitter visible-xs"></i>
					<span class="hidden-xs">Login met Twitter</span>
				</a>
			</div>
		</div>

		<div class="row col-md-offset-3 omb_loginOr">
			<div class="col-xs-12 col-md-8">
				<hr class="omb_hrOr">
				<span class="omb_spanOr">of</span>
			</div>
		</div>

		<div class="row col-md-offset-3">
			<div class="col-xs-12 col-md-8">	
				<form class="omb_loginForm" role="form" autocomplete="off" method="POST" action="{{ url('/auth/register') }}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					
					<div class="input-group">
						<span class="input-group-addon">&nbsp;<i class="fa fa-child fa-2x"></i>&nbsp;</span>
						<input type="text" class="form-control input-lg" name="name" required placeholder="Voornaam + Achternaam" value="{{ old('name') }}">
					</div>
					
					<br/>
					
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-envelope-o fa-2x"></i></span>
						<input type="email" class="form-control input-lg" name="email" required  placeholder="E-mailadres" value="{{ old('email') }}">				
					</div>
					
					<br/>
					
					<button type="submit" class="btn btn-lg btn-primary btn-block" onclick="ga('send', 'event', 'register', 'Original');">
						Antwoorden bekijken
					</button>
				</form>
			</div>
		</div>
</div>
					
					
				

@endsection
