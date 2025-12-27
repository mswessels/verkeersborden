@extends('app')

@section('content')

<?php
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate( 'D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
?>

<div class="container">
	<div class="row">
		<div class="col-md-3">
			<div class="well clearfix">
				@include('partials.sign-picture', [
					'image' => $image,
					'size' => 220,
					'class' => 'img-fluid',
					'alt' => 'Verkeersbord',
					'loading' => 'eager',
				])
			</div>
		</div>		
		<div class="col-md-8">
			
			<form id="quiz" role="form" autocomplete="off" method="GET" action="{{ url('/quiz') }}">
				
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				
				<h2 class="mt0">Wat betekent dit bord?</h2>
				
				<div class="funkyradio">
				
					<input type="radio" name="answer" value="0" checked="checked" style="display:none;"/>
					@foreach($options as $option)
					
					<div class="funkyradio-primary">
						<input type="radio" name="answer" value="{{ $option['id'] }}" id="{{ $option['id'] }}" />
						<label for="{{ $option['id'] }}"><span>{{ empty($option['description_short']) ? $option['description'] : $option['description_short'] }}</span></label>
					</div>
					
					@endforeach
					
				</div>
				
				<hr>
				
				<span class="meta-item float-end"><i class="fa fa-graduation-cap d-none d-md-inline"></i> {{ $count }} / 20</span>
				<button type="submit" class="btn btn-primary">Volgende vraag</button>
				<span class="meta-item"><i class="fa fa-history"></i> <span id="timer">20</span></span>
			</form>
			
		</div>
	</div>
</div>
@endsection

@section('footer_scripts')

<script>
var count = 20;
var counter = setInterval(timer, 1000);
function timer()
{
	count = count - 1;
	if (count < 0)
	{
		clearInterval(counter);
		document.getElementById("quiz").submit();
		return;
	}
	document.getElementById("timer").textContent = count;
}
</script>
@endsection
