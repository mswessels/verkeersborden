@extends('app')

@section('content')
<div class="container">
	<div class="row">
		@include('user.sidebar')
		<div class="col-md-8">
			@if(Session::has('message'))
				<div class="alert alert-success" role="alert">
					{{ Session::get('message') }}
				</div>
			@endif
			@if(count($errors->all()))
				<div class="alert alert-danger" role="alert">
					{{ $errors->all()[0] }}
				</div>
			@endif
			
			@if($cancelled)
				
			<h1>Your subscription is ended</h1>
			We won't bill you anymore.<br>
			However you're account remains active until {{ \Carbon\Carbon::parse(Auth::user()->subscription_ends_at)->format('d-m-Y') }}
			
			@else
			
			<h1>Are you sure?</h1>
			
			Are you sure that you want to cancel?<br/>
			On this page you can cancel your subscription.</br>
			You will still have access to our app until your subscription end date is met.</br></br>
			
			<form action="{{ Request::url() }}" method="POST">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />

				<div class="form-group">
					<input class="btn btn-link" type="submit" value="Yes, cancel my subscription">
				</div>
			</form>
			@endif
		</div>
	</div>
</div>
@endsection