<?php namespace Sanatorium\Rewards\Handlers\Reward;

use Sanatorium\Rewards\Models\Reward;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface RewardEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a reward is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a reward is created.
	 *
	 * @param  \Sanatorium\Rewards\Models\Reward  $reward
	 * @return mixed
	 */
	public function created(Reward $reward);

	/**
	 * When a reward is being updated.
	 *
	 * @param  \Sanatorium\Rewards\Models\Reward  $reward
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Reward $reward, array $data);

	/**
	 * When a reward is updated.
	 *
	 * @param  \Sanatorium\Rewards\Models\Reward  $reward
	 * @return mixed
	 */
	public function updated(Reward $reward);

	/**
	 * When a reward is deleted.
	 *
	 * @param  \Sanatorium\Rewards\Models\Reward  $reward
	 * @return mixed
	 */
	public function deleted(Reward $reward);

}
