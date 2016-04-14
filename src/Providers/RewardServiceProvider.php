<?php namespace Sanatorium\Rewards\Providers;

use Cartalyst\Support\ServiceProvider;

class RewardServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Rewards\Models\Reward']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.rewards.reward.handler.event');

		// Register all the default hooks
        $this->registerHooks();

        // Prepare resources
        $this->prepareResources();
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.rewards.reward', 'Sanatorium\Rewards\Repositories\Reward\RewardRepository');

		// Register the data handler
		$this->bindIf('sanatorium.rewards.reward.handler.data', 'Sanatorium\Rewards\Handlers\Reward\RewardDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.rewards.reward.handler.event', 'Sanatorium\Rewards\Handlers\Reward\RewardEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.rewards.reward.validator', 'Sanatorium\Rewards\Validator\Reward\RewardValidator');
	}

	/**
     * Prepare the package resources.
     *
     * @return void
     */
    protected function prepareResources()
    {
        $config = realpath(__DIR__.'/../../config/config.php');

        $this->mergeConfigFrom($config, 'sanatorium-rewards');

        $this->publishes([
            $config => config_path('sanatorium-rewards.php'),
        ], 'config');
    }

	/**
     * Register all hooks.
     *
     * @return void
     */
    protected function registerHooks()
    {
        $hooks = [
            [
            	'position' => 'order.placed.thanks',
            	'hook' => 'sanatorium/rewards::hooks.placed',
            ],
            [
            	'position' => 'profile.for.you',
            	'hook' => 'sanatorium/rewards::hooks.my',
            ]
        ];

        $manager = $this->app['sanatorium.hooks.manager'];

        foreach ($hooks as $item) {
        	extract($item);
            $manager->registerHook($position, $hook);
        }
    }
}
