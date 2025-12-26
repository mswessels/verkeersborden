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
			
			
			<h1>Your payments</h1>
			<table class="table table-striped">
				<thead>
					<tr class="info">
						<th>Date</th>
						<th>Payment</th>
						<th>Status</th>
						<th>Invoices</th>
					</tr>
				</thead>
				<tbody>
					@if(!empty($next_invoice))
					<tr>
						<td>{{ Carbon\Carbon::parse($user->trial_ends_at)->toFormattedDateString() }} (Next payment)</td>
						<td>{{ money_format('$%i', $next_invoice->amount/100) }}</td>
						<td>Due</td>
						<td></td>
					</tr>
					@endif
					
					@foreach($invoices as $invoice)
			
					<tr>
						<td>{{ Carbon\Carbon::createFromTimestamp($invoice->date)->toFormattedDateString() }}</td>
						<td>{{ $invoice->dollars() }}</td>
						<td>Paid</td>
						<td>{!! link_to('/user/invoice/'. $invoice->id,'View',['target'=>'_blank']) !!}</td> 
					</tr>
					
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection