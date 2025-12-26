@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-4 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Pay here</div>
				<div class="panel-body">
					<form action="" method="POST">
					  <script
						src="https://checkout.stripe.com/checkout.js" class="stripe-button"
						data-key="pk_test_5GQBsIiQSdk8zMVpMeFs6Vp4"
						data-amount="2000"
						data-name="Demo Site"
						data-description="2 widgets ($20.00)"
						data-image="/128x128.png">
					  </script>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
