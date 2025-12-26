<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Stripe\Coupon as Coupon;
use Stripe\Price as Price;
use Stripe\Stripe;

class StripeController extends Controller {

	public function __construct()
	{
		$this->user = Auth::user();
		Stripe::setApiKey(Config::get('services.stripe.secret'));
	}

	public function getPayment()
	{
		return view('user.payment');
	}	
	
	public function postPayment()
	{
		if(request()->has('stripeToken'))
		{
			$token = request('stripeToken');

			$plan = \App\Plan::orderBy('id')->value('stripe_id');
			if (! $plan) {
				return Redirect::back()->withErrors(['message' => 'No subscription plan configured.']);
			}

			$this->user->newSubscription('default', $plan)->create($token);
			
			return Redirect::to('/user/welcome');
		}
		else
		{
			return Redirect::back();
		}
	}
	
	/* Changed subscription via stripe */
	public function getSubscription()
	{
		$subscription = $this->user->subscription('default');
		$data = array(
			'plans' => \App\Plan::orderBy('id')->pluck('name','stripe_id'),
			'cancelled' => $subscription ? $subscription->cancelled() : false,
			'myPlan' => $subscription ? $subscription->stripe_price : null,
		);
		return view('user.subscription')->with($data);
	}
		
	public function postSubscription()
	{
		if(request()->has('plan'))
		{
			$newPlan = request('plan');
			if(\App\Plan::where('stripe_id',$newPlan)->first())
			{
				try {
					
					/* Allows user to swap plans and add coupon to next billing */
					if(request()->has('coupon') && !empty(request('coupon'))) {
						try {
							$coupon = Coupon::retrieve(request('coupon')); //check coupon exists
							if($coupon->times_redeemed >= $coupon->max_redemptions)
								return redirect()->back()->withErrors(['message' => trans('stripe.couponoverused')]);
						} catch (\Stripe\Exception\InvalidRequestException $e) {
							return redirect()->back()->withErrors(['message' => trans('stripe.invalidcoupon')]);
						}
						$this->user->applyCoupon(request('coupon'));
					}
					
					$subscription = $this->user->subscription('default');
					if (! $subscription) {
						return redirect()->back()->withErrors(['message' => trans('stripe.subscription_error')]);
					}

					$subscription->swap($newPlan);

				} catch(\Stripe\Exception\CardException $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.card')]);
				} catch (\Stripe\Exception\InvalidRequestException $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.invalidrequest')]);
				} catch (\Stripe\Exception\AuthenticationException $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.authentication')]);
				} catch (\Stripe\Exception\ApiConnectionException $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.network')]);
				} catch (\Stripe\Exception\ApiErrorException $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.basic')]);
				} catch (Exception $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.basic')]);
				}
				
				if ($this->user->subscribed('default', $newPlan)) {
					
					return redirect()->back()->with(['message' => trans('stripe.subscription_changed')]);
				
				}			
			}
		}
		
		return redirect()->back()->withErrors(['message' => trans('stripe.subscription_error')]);
	}
	
	
	/* Add Coupon to subscription via stripe */
	public function getCoupon()
	{
		$subscription = $this->user->subscription('default');
		$data = array(
			'plans' => \App\Plan::orderBy('id')->pluck('name','stripe_id'),
			'cancelled' => $subscription ? $subscription->cancelled() : false,
			'myPlan' => $subscription ? $subscription->stripe_price : null,
			'subscribed' => $this->user->subscribed('default') ? true : false,
		);
		return view('user.coupon')->with($data);
	}
	
	public function postCoupon()
	{
		if(request()->has('coupon')
			&& !empty(request('coupon'))
			&& $this->user->subscribed('default')) {
			
			try {
				/* Allows user to swap plans and add coupon to next billing */
				
				try {
					$coupon = Coupon::retrieve(request('coupon')); //check coupon exists
					if($coupon->times_redeemed >= $coupon->max_redemptions)
						return redirect()->back()->withErrors(['message' => trans('stripe.couponoverused')]);
				} catch (\Stripe\Exception\InvalidRequestException $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.invalidcoupon')]);
				}
				
				$this->user->applyCoupon(request('coupon'));
				
				return redirect()->back()->with(['message' => trans('stripe.coupon_added')]);

			} catch(\Stripe\Exception\CardException $e) {
				return redirect()->back()->withErrors(['message' => trans('stripe.card')]);
			} catch (\Stripe\Exception\InvalidRequestException $e) {
				return redirect()->back()->withErrors(['message' => trans('stripe.invalidrequest')]);
			} catch (\Stripe\Exception\AuthenticationException $e) {
				return redirect()->back()->withErrors(['message' => trans('stripe.authentication')]);
			} catch (\Stripe\Exception\ApiConnectionException $e) {
				return redirect()->back()->withErrors(['message' => trans('stripe.network')]);
			} catch (\Stripe\Exception\ApiErrorException $e) {
				return redirect()->back()->withErrors(['message' => trans('stripe.basic')]);
			} catch (Exception $e) {
				return redirect()->back()->withErrors(['message' => trans('stripe.basic')]);
			}
		}
		
		return redirect()->back()->withErrors(['message' => trans('stripe.invalidcoupon')]);
	}
	
	/* Get invoices via stripe */
	public function getInvoices()
	{
		$subscription = $this->user->subscription('default');
		if ($subscription && $subscription->valid() && !empty($subscription->stripe_price)) {
			$next_invoice = Price::retrieve($subscription->stripe_price);
		}
		$data = array(
			'next_invoice' => isset($next_invoice) ? $next_invoice : null,
			'invoices' => $this->user->invoices(),
			'user' => $this->user,
		);
		return view('user.invoices')->with($data);
	}
	
	public function getSingleInvoice($id)
	{		
		if(!$this->user->findInvoice($id))
			abort(404);

		$subscription = $this->user->subscription('default');
		$product = null;
		$productName = null;
		if ($subscription && !empty($subscription->stripe_price)) {
			$product = Price::retrieve($subscription->stripe_price, ['expand' => ['product']]);
			$productName = $product->product->name ?? $product->nickname ?? $product->id;
		}

		$data = array(
			'invoice' => $this->user->findInvoice($id),
			'product' => $product,
			'product_name' => $productName,
			'billable' => $this->user,
		);
		
		return view('user.invoice')->with($data);
	}
	
	/* Cancel subscription via stripe */
	public function getCard()
	{
		$subscription = $this->user->subscription('default');
		$data = array(
			'cancelled' => $subscription ? $subscription->cancelled() : false
		);
		return view('user.card')->with($data);
	}
	
	/* Cancel subscription via stripe */
	public function getCancel()
	{
		$subscription = $this->user->subscription('default');
		$data = array(
			'cancelled' => $subscription ? $subscription->cancelled() : false
		);
		return view('user.cancel')->with($data);
	}
		
	public function postCancel()
	{
		$subscription = $this->user->subscription('default');

		if ($subscription && $subscription->valid()) {
			
			$subscription->cancel();
			
			if ($subscription->cancelled()) {
				
				return redirect()->back()->with(['message' => trans('stripe.unsubscribed')]);
			
			}
		}
		
		return redirect()->back()->withErrors(['message' => trans('stripe.unsubscribe_error')]);
	}

}
