<?php namespace Sanatorium\Rewards\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Rewards\Repositories\Reward\RewardRepositoryInterface;

class RewardsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Rewards repository.
	 *
	 * @var \Sanatorium\Rewards\Repositories\Reward\RewardRepositoryInterface
	 */
	protected $rewards;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Rewards\Repositories\Reward\RewardRepositoryInterface  $rewards
	 * @return void
	 */
	public function __construct(RewardRepositoryInterface $rewards)
	{
		parent::__construct();

		$this->rewards = $rewards;
	}

	/**
	 * Display a listing of reward.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/rewards::rewards.index');
	}

	/**
	 * Datasource for the reward Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->rewards->grid();

		$columns = [
			'id',
			'user_id',
			'reward',
			'plus',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.rewards.rewards.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new reward.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new reward.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating reward.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating reward.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified reward.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->rewards->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/rewards::rewards/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.rewards.rewards.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->rewards->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a reward identifier?
		if (isset($id))
		{
			if ( ! $reward = $this->rewards->find($id))
			{
				$this->alerts->error(trans('sanatorium/rewards::rewards/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.rewards.rewards.all');
			}
		}
		else
		{
			$reward = $this->rewards->createModel();
		}

		// Show the page
		return view('sanatorium/rewards::rewards.form', compact('mode', 'reward'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the reward
		list($messages) = $this->rewards->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/rewards::rewards/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.rewards.rewards.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
