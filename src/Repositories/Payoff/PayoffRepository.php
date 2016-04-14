<?php namespace Sanatorium\Rewards\Repositories\Payoff;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class PayoffRepository implements PayoffRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Rewards\Handlers\Payoff\PayoffDataHandlerInterface
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

		$this->data = $app['sanatorium.rewards.payoff.handler.data'];

		$this->setValidator($app['sanatorium.rewards.payoff.validator']);

		$this->setModel(get_class($app['Sanatorium\Rewards\Models\Payoff']));
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
		return $this->container['cache']->rememberForever('sanatorium.rewards.payoff.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.rewards.payoff.'.$id, function() use ($id)
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
		// Create a new payoff
		$payoff = $this->createModel();

		// Fire the 'sanatorium.rewards.payoff.creating' event
		if ($this->fireEvent('sanatorium.rewards.payoff.creating', [ $input ]) === false)
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
			// Save the payoff
			$payoff->fill($data)->save();

			// Fire the 'sanatorium.rewards.payoff.created' event
			$this->fireEvent('sanatorium.rewards.payoff.created', [ $payoff ]);
		}

		return [ $messages, $payoff ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the payoff object
		$payoff = $this->find($id);

		// Fire the 'sanatorium.rewards.payoff.updating' event
		if ($this->fireEvent('sanatorium.rewards.payoff.updating', [ $payoff, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($payoff, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the payoff
			$payoff->fill($data)->save();

			// Fire the 'sanatorium.rewards.payoff.updated' event
			$this->fireEvent('sanatorium.rewards.payoff.updated', [ $payoff ]);
		}

		return [ $messages, $payoff ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the payoff exists
		if ($payoff = $this->find($id))
		{
			// Fire the 'sanatorium.rewards.payoff.deleted' event
			$this->fireEvent('sanatorium.rewards.payoff.deleted', [ $payoff ]);

			// Delete the payoff entry
			$payoff->delete();

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
