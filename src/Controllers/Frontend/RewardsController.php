<?php namespace Sanatorium\Rewards\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;
use Cookie;

class RewardsController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/rewards::index');
	}

	public function invite($by = null)
	{
		Cookie::queue('invited_by', $by, 60*24*365);

		return redirect()->to( route('user.register') );
	}

}
