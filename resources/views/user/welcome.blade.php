@extends('app')

@section('content')
<div class="container">
	<div class="row">
		@include('user.sidebar')
		<div class="col-md-8">
						
			<h1>Hi, You!</h1>
			
			Awesome welcome message.
			
		</div>
	</div>
</div>
@endsection