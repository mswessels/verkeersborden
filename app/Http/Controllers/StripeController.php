<?php namespace App\Http\Controllers;

use Input, Auth, Redirect, Config, User;
use Stripe\Coupon as Coupon;
use Stripe\Plan as Plan;
use Laravel\Cashier\StripeGateway as Invoice;
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
		if(Input::has('stripeToken'))
		{
			$token = Input::get('stripeToken');
			
			$this->user->subscription(1)->create($token);
			
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
		$data = array(
			'plans' => \App\Plan::orderBy('id')->lists('name','stripe_id'),
			'cancelled' => $this->user->cancelled() ? true : false,
			'myPlan' => $this->user->cancelled() ?: $this->user->stripe_plan,
		);
		return view('user.subscription')->with($data);
	}
		
	public function postSubscription()
	{
		if(Input::has('plan'))
		{
			$newPlan = Input::get('plan');
			if(\App\Plan::where('stripe_id',$newPlan)->first())
			{
				try {
					
					/* Allows user to swap plans and add coupon to next billing */
					if(Input::has('coupon') && !empty(Input::get('coupon'))) {
						try {
							$coupon = Coupon::retrieve(Input::get('coupon')); //check coupon exists
							if($coupon->times_redeemed >= $coupon->max_redemptions)
								return redirect()->back()->withErrors(['message' => trans('stripe.couponoverused')]);
						} catch (\Stripe\Error\InvalidRequest $e) {
							return redirect()->back()->withErrors(['message' => trans('stripe.invalidcoupon')]);
						}
						$this->user->applyCoupon(Input::get('coupon'));
					}
					
					$this->user->subscription($newPlan)->swap();

				} catch(\Stripe\Error\Card $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.card')]);
				} catch (\Stripe\Error\InvalidRequest $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.invalidrequest')]);
				} catch (\Stripe\Error\Authentication $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.authentication')]);
				} catch (\Stripe\Error\ApiConnection $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.network')]);
				} catch (\Stripe\Error\Base $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.basic')]);
				} catch (Exception $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.basic')]);
				}
				
				if ($this->user->subscribed($newPlan)) {
					
					return redirect()->back()->with(['message' => trans('stripe.subscription_changed')]);
				
				}			
			}
		}
		
		return redirect()->back()->withErrors(['message' => trans('stripe.subscription_error')]);
	}
	
	
	/* Add Coupon to subscription via stripe */
	public function getCoupon()
	{
		$data = array(
			'plans' => \App\Plan::orderBy('id')->lists('name','stripe_id'),
			'cancelled' => $this->user->cancelled() ? true : false,
			'myPlan' => $this->user->cancelled() ?: $this->user->stripe_plan,
			'subscribed' => $this->user->subscribed() ? true : false,
		);
		return view('user.coupon')->with($data);
	}
	
	public function postCoupon()
	{
		if(Input::has('coupon')
			&& !empty(Input::get('coupon'))
			&& $this->user->subscribed()) {
			
			try {
				/* Allows user to swap plans and add coupon to next billing */
				
				try {
					$coupon = Coupon::retrieve(Input::get('coupon')); //check coupon exists
					if($coupon->times_redeemed >= $coupon->max_redemptions)
						return redirect()->back()->withErrors(['message' => trans('stripe.couponoverused')]);
				} catch (\Stripe\Error\InvalidRequest $e) {
					return redirect()->back()->withErrors(['message' => trans('stripe.invalidcoupon')]);
				}
				
				$this->user->applyCoupon(Input::get('coupon'));
				
				return redirect()->back()->with(['message' => trans('stripe.coupon_added')]);

			} catch(\Stripe\Error\Card $e) {
				return redirect()->back()->withErrors(['message' => trans('stripe.card')]);
			} catch (\Stripe\Error\InvalidRequest $e) {
				return redirect()->back()->withErrors(['message' => trans('stripe.invalidrequest')]);
			} catch (\Stripe\Error\Authentication $e) {
				return redirect()->back()->withErrors(['message' => trans('stripe.authentication')]);
			} catch (\Stripe\Error\ApiConnection $e) {
				return redirect()->back()->withErrors(['message' => trans('stripe.network')]);
			} catch (\Stripe\Error\Base $e) {
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
		if(!$this->user->cancelled() && $this->user->subscribed()) {
			$next_invoice = Plan::retrieve($this->user->stripe_plan);
		}
		$data = array(
			'next_invoice' => !isset($next_invoice) ?: $next_invoice,
			'invoices' => $this->user->invoices(),
			'user' => $this->user,
		);
		return view('user.invoices')->with($data);
	}
	
	public function getSingleInvoice($id)
	{		
		if(!$this->user->findInvoice($id))
			App::abort(404);
		
		$data = array(
			'invoice' => $this->user->findInvoice($id),
			'product' => Plan::retrieve($this->user->stripe_plan),
			'billable' => $this->user,
		);
		
		return view('user.invoice')->with($data);
	}
	
	/* Cancel subscription via stripe */
	public function getCard()
	{
		$data = array(
			'cancelled' => $this->user->cancelled() ? true : false
		);
		return view('user.card')->with($data);
	}
	
	/* Cancel subscription via stripe */
	public function getCancel()
	{
		$data = array(
			'cancelled' => $this->user->cancelled() ? true : false
		);
		return view('user.cancel')->with($data);
	}
		
	public function postCancel()
	{
		
		if ($this->user->subscribed()) {
			
			$this->user->subscription()->cancel();
			
			if ($this->user->cancelled()) {
				
				return redirect()->back()->with(['message' => trans('stripe.unsubscribed')]);
			
			}
		}
		
		return redirect()->back()->withErrors(['message' => trans('stripe.unsubscribe_error')]);
	}

}
