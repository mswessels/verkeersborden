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
			
			<h1>Have a coupon?</h1>
			
			@if($cancelled)
				
			<strong>You don't have a subscription (anymore).</strong><br>
			Would you like to subscribe to a plan?<br>
			You can also add a coupon there.<br>
			{!! link_to('user/subscription', 'Click here') !!}
			
			@else
				
			<form action="{{ Request::url() }}" method="POST">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				
				<div class="form-group">
					<label for="name">Do you have a coupon?</label>
					<input class="form-control" name="coupon" type="text" id="coupon" placeholder="Enter it here and we will aply it to your next billing.">						
				</div>
				
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Add coupon to next bill">
				</div>
			</form>
			@endif
		</div>
	</div>
</div>
@endsection