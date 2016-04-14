<?php namespace Sanatorium\Rewards\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Sentinel;
use Platform\Users\Models\User;
use Sanatorium\Rewards\Models\Uservoucher;

class PayoffsController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/rewards::index');
	}

	/**
	*	$payoff_1 = Payoff::find($id);
	*
	*	$payoffs = app('sanatorium.rewards.payoff');
	*	$payoff_2 = $payoffs->find($id);
	*/
	public function redeem($id)
	{
		// Find payoff
		$payoffs = app('sanatorium.rewards.payoff');
		$payoff = $payoffs->find($id);

		if ( !$payoff )
			return redirect()->back()->withErrors(['Payoff not found']);

		// Find current user
		$user = Sentinel::getUser();

		if ( !$user )
			return redirect()->back()->withErrors(['User is not logged in']);

		// Current user points
		$total = $this->pointsByUser($user);

		if ( $payoff->points > $total )
			return redirect()->back()->withErrors(['Not enough reward points']);

		// Subtract points from user account
		$rewards = app('sanatorium.rewards.reward');

		list($messages, $reward) = $rewards->create([
			'user_id' => $user->id,
			'entity_id' => $payoff->id,
			'reward' => (int)$payoff->points,
			'plus' => false
			]);

		$vouchers = app('sanatorium.discounts.voucher');

		$code = $this->generateRandomUniqueString(10);

		list($messages, $voucher) = $vouchers->create([
			'code' => $code,
			'limit' => 1,
			'absolute' => $payoff->payoff_money
			]);

		Uservoucher::create([
			'user_id' => $user->id,
			'voucher_id' => $voucher->id,
			]);

		$this->alerts->success(
			sprintf('Your new code is %s', $code)
			);

		return redirect()->back();

	}

	public function pointsByUser($user)
	{
		if ( is_numeric($user) )
			$user = User::find($user);

		if ( !is_object($user) )
			return 0;

		$rewards = app('sanatorium.rewards.reward');

		$income = $rewards->where('user_id', $user->id)->where('plus', true)->sum('reward');
		$outcome = $rewards->where('user_id', $user->id)->where('plus', false)->sum('reward');

		$total = $income - $outcome;

		return $total;

	}

	public function generateRandomUniqueString($length = 10)
	{
		$code = $this->generateRandomString($length);

		$vouchers = app('sanatorium.discounts.voucher');

		// If voucher with the same code is found
		// call the function again
		if ( $vouchers->where('code', $code)->count() > 0 )
			return $this->generateRandomUniqueString($length);

		return $code;
	}

	public function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

}
