<?php namespace Sanatorium\Rewards\Repositories\Reward;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class RewardRepository implements RewardRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Rewards\Handlers\Reward\RewardDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent rewards model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.rewards.reward.handler.data'];

		$this->setValidator($app['sanatorium.rewards.reward.validator']);

		$this->setModel(get_class($app['Sanatorium\Rewards\Models\Reward']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.rewards.reward.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.rewards.reward.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new reward
		$reward = $this->createModel();

		// Fire the 'sanatorium.rewards.reward.creating' event
		if ($this->fireEvent('sanatorium.rewards.reward.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the reward
			$reward->fill($data)->save();

			// Fire the 'sanatorium.rewards.reward.created' event
			$this->fireEvent('sanatorium.rewards.reward.created', [ $reward ]);
		}

		return [ $messages, $reward ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the reward object
		$reward = $this->find($id);

		// Fire the 'sanatorium.rewards.reward.updating' event
		if ($this->fireEvent('sanatorium.rewards.reward.updating', [ $reward, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($reward, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the reward
			$reward->fill($data)->save();

			// Fire the 'sanatorium.rewards.reward.updated' event
			$this->fireEvent('sanatorium.rewards.reward.updated', [ $reward ]);
		}

		return [ $messages, $reward ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the reward exists
		if ($reward = $this->find($id))
		{
			// Fire the 'sanatorium.rewards.reward.deleted' event
			$this->fireEvent('sanatorium.rewards.reward.deleted', [ $reward ]);

			// Delete the reward entry
			$reward->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
