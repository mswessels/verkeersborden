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
					@php
						$nextAmount = $next_invoice->unit_amount ?? $next_invoice->unit_amount_decimal ?? 0;
						$nextCurrency = $next_invoice->currency ?? 'usd';
						$nextPaymentDate = $user->trial_ends_at
							? Carbon\Carbon::parse($user->trial_ends_at)->toFormattedDateString()
							: 'N/A';
					@endphp
					<tr>
						<td>{{ $nextPaymentDate }} (Next payment)</td>
						<td>{{ \Laravel\Cashier\Cashier::formatAmount((int) $nextAmount, $nextCurrency) }}</td>
						<td>Due</td>
						<td></td>
					</tr>
					@endif
					
					@foreach($invoices as $invoice)
			
					<tr>
						<td>{{ $invoice->date()->toFormattedDateString() }}</td>
						<td>{{ $invoice->total() }}</td>
						<td>Paid</td>
						<td><a href="{{ url('/user/invoice/' . $invoice->id) }}" target="_blank">View</a></td>
					</tr>
					
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
