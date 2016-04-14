<?php namespace Sanatorium\Rewards\Handlers\Uservoucher;

use Sanatorium\Rewards\Models\Uservoucher;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface UservoucherEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a uservoucher is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a uservoucher is created.
	 *
	 * @param  \Sanatorium\Rewards\Models\Uservoucher  $uservoucher
	 * @return mixed
	 */
	public function created(Uservoucher $uservoucher);

	/**
	 * When a uservoucher is being updated.
	 *
	 * @param  \Sanatorium\Rewards\Models\Uservoucher  $uservoucher
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Uservoucher $uservoucher, array $data);

	/**
	 * When a uservoucher is updated.
	 *
	 * @param  \Sanatorium\Rewards\Models\Uservoucher  $uservoucher
	 * @return mixed
	 */
	public function updated(Uservoucher $uservoucher);

	/**
	 * When a uservoucher is deleted.
	 *
	 * @param  \Sanatorium\Rewards\Models\Uservoucher  $uservoucher
	 * @return mixed
	 */
	public function deleted(Uservoucher $uservoucher);

}
