<?php namespace Sanatorium\Rewards\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Rewards\Repositories\Payoff\PayoffRepositoryInterface;

class PayoffsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Rewards repository.
	 *
	 * @var \Sanatorium\Rewards\Repositories\Payoff\PayoffRepositoryInterface
	 */
	protected $payoffs;

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
	 * @param  \Sanatorium\Rewards\Repositories\Payoff\PayoffRepositoryInterface  $payoffs
	 * @return void
	 */
	public function __construct(PayoffRepositoryInterface $payoffs)
	{
		parent::__construct();

		$this->payoffs = $payoffs;
	}

	/**
	 * Display a listing of payoff.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/rewards::payoffs.index');
	}

	/**
	 * Datasource for the payoff Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->payoffs->grid();

		$columns = [
			'id',
			'points',
			'payoff_money',
			'payoff_id',
			'payoff_type',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.rewards.payoffs.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new payoff.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new payoff.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating payoff.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating payoff.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified payoff.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->payoffs->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/rewards::payoffs/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.rewards.payoffs.all');
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
				$this->payoffs->{$action}($row);
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
		// Do we have a payoff identifier?
		if (isset($id))
		{
			if ( ! $payoff = $this->payoffs->find($id))
			{
				$this->alerts->error(trans('sanatorium/rewards::payoffs/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.rewards.payoffs.all');
			}
		}
		else
		{
			$payoff = $this->payoffs->createModel();
		}

		// Show the page
		return view('sanatorium/rewards::payoffs.form', compact('mode', 'payoff'));
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
		// Store the payoff
		list($messages) = $this->payoffs->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/rewards::payoffs/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.rewards.payoffs.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
