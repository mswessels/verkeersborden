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
			
			<h1>Your subscription</h1>
			
			@if($cancelled)
				
			<strong>You don't have a subscription (anymore).</strong><br/>
			
			@endif			
				
			<form action="{{ Request::url() }}" method="POST">
				<input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
				
				<div class="form-group">
					<label for="plan">Do You want to Change or Upgrade Your Subscription?</label>

					{!! Form::select('plan', [null=>'You don\'t have a subscription yet.'] + $plans, $myPlan,array('class'=>'form-control')) !!}
				</div>
				
				<div class="form-group">
					<label for="name">Do you have a coupon?</label>
					<input class="form-control" name="coupon" type="text" id="coupon" placeholder="Enter it here and we will aply it to your next billing.">						
				</div>
				
				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Change plan">
				</div>
			</form>
		</div>
	</div>
</div>
@endsection