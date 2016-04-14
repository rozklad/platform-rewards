<?php namespace Sanatorium\Rewards\Handlers\Payoff;

use Sanatorium\Rewards\Models\Payoff;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface PayoffEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a payoff is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a payoff is created.
	 *
	 * @param  \Sanatorium\Rewards\Models\Payoff  $payoff
	 * @return mixed
	 */
	public function created(Payoff $payoff);

	/**
	 * When a payoff is being updated.
	 *
	 * @param  \Sanatorium\Rewards\Models\Payoff  $payoff
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Payoff $payoff, array $data);

	/**
	 * When a payoff is updated.
	 *
	 * @param  \Sanatorium\Rewards\Models\Payoff  $payoff
	 * @return mixed
	 */
	public function updated(Payoff $payoff);

	/**
	 * When a payoff is deleted.
	 *
	 * @param  \Sanatorium\Rewards\Models\Payoff  $payoff
	 * @return mixed
	 */
	public function deleted(Payoff $payoff);

}
