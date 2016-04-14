<?php namespace Sanatorium\Rewards\Handlers\Reward;

use Illuminate\Events\Dispatcher;
use Sanatorium\Rewards\Models\Reward;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class RewardEventHandler extends BaseEventHandler implements RewardEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.rewards.reward.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.rewards.reward.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.rewards.reward.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.rewards.reward.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.rewards.reward.deleted', __CLASS__.'@deleted');

		$dispatcher->listen('sanatorium.rewards.invited_by', __CLASS__.'@invited');
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
	public function created(Reward $reward)
	{
		$this->flushCache($reward);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Reward $reward, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Reward $reward)
	{
		$this->flushCache($reward);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Reward $reward)
	{
		$this->flushCache($reward);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Rewards\Models\Reward  $reward
	 * @return void
	 */
	protected function flushCache(Reward $reward)
	{
		$this->app['cache']->forget('sanatorium.rewards.reward.all');

		$this->app['cache']->forget('sanatorium.rewards.reward.'.$reward->id);
	}

	public function invited($invited_by = null)
	{
		$users = app('platform.users');

		$user = $users->where('email', $invited_by)->first();

		if ( !$user )
			return false;
		
		$rewards = app('sanatorium.rewards.reward');

		$reward = $rewards->create([
			'user_id' => $user->id,
			'entity_id' => 0,	// @todo save new user id
			'reward' => 10,	// @todo make dynamic
			'plus' => true
			]);

		return true;
	}

}
