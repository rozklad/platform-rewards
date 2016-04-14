<?php namespace Sanatorium\Rewards\Handlers\Uservoucher;

use Illuminate\Events\Dispatcher;
use Sanatorium\Rewards\Models\Uservoucher;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class UservoucherEventHandler extends BaseEventHandler implements UservoucherEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.rewards.uservoucher.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.rewards.uservoucher.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.rewards.uservoucher.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.rewards.uservoucher.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.rewards.uservoucher.deleted', __CLASS__.'@deleted');
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
	public function created(Uservoucher $uservoucher)
	{
		$this->flushCache($uservoucher);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Uservoucher $uservoucher, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Uservoucher $uservoucher)
	{
		$this->flushCache($uservoucher);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Uservoucher $uservoucher)
	{
		$this->flushCache($uservoucher);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Rewards\Models\Uservoucher  $uservoucher
	 * @return void
	 */
	protected function flushCache(Uservoucher $uservoucher)
	{
		$this->app['cache']->forget('sanatorium.rewards.uservoucher.all');

		$this->app['cache']->forget('sanatorium.rewards.uservoucher.'.$uservoucher->id);
	}

}
