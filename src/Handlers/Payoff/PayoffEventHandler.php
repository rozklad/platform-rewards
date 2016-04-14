<?php namespace Sanatorium\Rewards\Handlers\Payoff;

use Illuminate\Events\Dispatcher;
use Sanatorium\Rewards\Models\Payoff;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class PayoffEventHandler extends BaseEventHandler implements PayoffEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.rewards.payoff.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.rewards.payoff.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.rewards.payoff.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.rewards.payoff.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.rewards.payoff.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Payoff $payoff)
	{
		$this->flushCache($payoff);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Payoff $payoff, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Payoff $payoff)
	{
		$this->flushCache($payoff);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Payoff $payoff)
	{
		$this->flushCache($payoff);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Rewards\Models\Payoff  $payoff
	 * @return void
	 */
	protected function flushCache(Payoff $payoff)
	{
		$this->app['cache']->forget('sanatorium.rewards.payoff.all');

		$this->app['cache']->forget('sanatorium.rewards.payoff.'.$payoff->id);
	}

}
