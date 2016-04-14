<?php namespace Sanatorium\Rewards\Widgets;

use Cookie;
use Sentinel;

class Hooks {

	public function placed($args = null)
	{
		if ( !$user = Sentinel::getUser() ) {
			// Not registered user does not get rewards
			return view('sanatorium/rewards::hooks/not_rewarded');
		}

		$rewards = app('sanatorium.rewards.reward');

		$points = config('sanatorium-rewards.order_placed');
		
		$order = $args['order'];

		if ( $rewarded = $rewards->where('entity_id', $order->id)->where('user_id', $user->id)->first() ) {
			// User already got reward for this order
			return null;
		}

		// Check if online payment was chosen
		$payment_online = false;
		$payment_success = false;

		$payment_service = $order->payment_service;

		if ( class_exists($payment_service) ) {

			$payment_service = new $payment_service;

			if ( method_exists($payment_service, 'isSuccess') ) {

				$payment_online = true;
				$payment_success = $payment_service->isSuccess($order);

			}

		}

		if ( $payment_online && $payment_success ) {
			$reward = $rewards->create([
				'user_id' => $user->id,
				'entity_id' => $order->id,
				'reward' => (int)$points,
				'plus' => true
				]);

			if ( Cookie::has('invited_by') ) {

				$invited_by = Cookie::get('invited_by');

				$users = app('platform.users');

				$user = $users->where('email', $invited_by)->first();

				if ( is_object($user) ) {
				
					$reward = $rewards->create([
						'user_id' => $user->id,
						'entity_id' => 0,	// @todo save new user id
						'reward' => 5,	// @todo make dynamic
						'plus' => true
						]);

				}

			}

			return view('sanatorium/rewards::hooks/placed', compact('reward', 'order', 'points')); 
		}

		return null;
	}

	public function my()
	{
		if ( !$user = Sentinel::getUser() ) {
			return null;
		}

		$rewards = app('sanatorium.rewards.reward');

		$points = config('sanatorium-rewards.order_placed');

		$income = $rewards->where('user_id', $user->id)->where('plus', true)->sum('reward');
		$outcome = $rewards->where('user_id', $user->id)->where('plus', false)->sum('reward');

		$total = $income - $outcome;

		$balances = $rewards->where('user_id', $user->id)->get();

		return view('sanatorium/rewards::hooks/my', compact('income', 'outcome', 'total', 'balances', 'points'));
	}

}
