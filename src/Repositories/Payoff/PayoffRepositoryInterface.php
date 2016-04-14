<?php namespace Sanatorium\Rewards\Repositories\Payoff;

interface PayoffRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Rewards\Models\Payoff
	 */
	public function grid();

	/**
	 * Returns all the rewards entries.
	 *
	 * @return \Sanatorium\Rewards\Models\Payoff
	 */
	public function findAll();

	/**
	 * Returns a rewards entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Rewards\Models\Payoff
	 */
	public function find($id);

	/**
	 * Determines if the given rewards is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given rewards is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given rewards.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a rewards entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Rewards\Models\Payoff
	 */
	public function create(array $data);

	/**
	 * Updates the rewards entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Rewards\Models\Payoff
	 */
	public function update($id, array $data);

	/**
	 * Deletes the rewards entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
