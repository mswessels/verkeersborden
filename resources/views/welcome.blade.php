@extends('app')

@section('content')
<div class="row">
	<div class="col-md-8 offset-md-2">
		<div class="well text-center">
			<h1 class="mt0">Welkom bij DeVerkeersborden.nl</h1>
			<p class="text-muted">{{ Inspiring::quote() }}</p>
			<a class="btn btn-primary" href="{{ url('/') }}">Naar de homepage</a>
		</div>
	</div>
</div>
@endsection
