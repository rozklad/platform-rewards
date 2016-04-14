<?php namespace Sanatorium\Rewards\Providers;

use Cartalyst\Support\ServiceProvider;

class PayoffServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Rewards\Models\Payoff']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.rewards.payoff.handler.event');
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.rewards.payoff', 'Sanatorium\Rewards\Repositories\Payoff\PayoffRepository');

		// Register the data handler
		$this->bindIf('sanatorium.rewards.payoff.handler.data', 'Sanatorium\Rewards\Handlers\Payoff\PayoffDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.rewards.payoff.handler.event', 'Sanatorium\Rewards\Handlers\Payoff\PayoffEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.rewards.payoff.validator', 'Sanatorium\Rewards\Validator\Payoff\PayoffValidator');
	}

}
