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
			<form action="{{ Request::url() }}" method="POST">
				<input type="hidden" name="_token" value="{{ csrf_token() }}" />
				
				<div class="form-group">
					<label for="name">Name:</label>
					<input class="form-control" required="required" name="name" type="text" value="{{ Auth::user()->name }}" id="name">						
				</div>

				<div class="form-group">
					<label for="email">Email:</label>
					<input class="form-control" required="required" name="email" type="email" value="{{ Auth::user()->email }}" id="email">					
				</div>

				<div class="form-group">
					<label for="password">Password:</label>
					<input class="form-control" name="password" type="password" value="" id="password">
				</div>

				<div class="form-group">
					<label for="password_confirmation">Password Confirmation:</label>
					<input class="form-control" name="password_confirmation" type="password" value="" id="password_confirmation">					
				</div>

				<div class="form-group">
					<input class="btn btn-primary" type="submit" value="Update Profile">
				</div>
			</form>
		</div>
	</div>
</div>
@endsection