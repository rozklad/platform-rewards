<?php

use Illuminate\Foundation\Application;
use Cartalyst\Extensions\ExtensionInterface;
use Cartalyst\Settings\Repository as Settings;
use Cartalyst\Permissions\Container as Permissions;

return [

	/*
	|--------------------------------------------------------------------------
	| Name
	|--------------------------------------------------------------------------
	|
	| This is your extension name and it is only required for
	| presentational purposes.
	|
	*/

	'name' => 'Rewards',

	/*
	|--------------------------------------------------------------------------
	| Slug
	|--------------------------------------------------------------------------
	|
	| This is your extension unique identifier and should not be changed as
	| it will be recognized as a new extension.
	|
	| Ideally, this should match the folder structure within the extensions
	| folder, but this is completely optional.
	|
	*/

	'slug' => 'sanatorium/rewards',

	/*
	|--------------------------------------------------------------------------
	| Author
	|--------------------------------------------------------------------------
	|
	| Because everybody deserves credit for their work, right?
	|
	*/

	'author' => 'Sanatorium',

	/*
	|--------------------------------------------------------------------------
	| Description
	|--------------------------------------------------------------------------
	|
	| One or two sentences describing the extension for users to view when
	| they are installing the extension.
	|
	*/

	'description' => 'Rewards',

	/*
	|--------------------------------------------------------------------------
	| Version
	|--------------------------------------------------------------------------
	|
	| Version should be a string that can be used with version_compare().
	| This is how the extensions versions are compared.
	|
	*/

	'version' => '0.1.2',

	/*
	|--------------------------------------------------------------------------
	| Requirements
	|--------------------------------------------------------------------------
	|
	| List here all the extensions that this extension requires to work.
	| This is used in conjunction with composer, so you should put the
	| same extension dependencies on your main composer.json require
	| key, so that they get resolved using composer, however you
	| can use without composer, at which point you'll have to
	| ensure that the required extensions are available.
	|
	*/

	'require' => [
		
	],

	/*
	|--------------------------------------------------------------------------
	| Autoload Logic
	|--------------------------------------------------------------------------
	|
	| You can define here your extension autoloading logic, it may either
	| be 'composer', 'platform' or a 'Closure'.
	|
	| If composer is defined, your composer.json file specifies the autoloading
	| logic.
	|
	| If platform is defined, your extension receives convetion autoloading
	| based on the Platform standards.
	|
	| If a Closure is defined, it should take two parameters as defined
	| bellow:
	|
	|	object \Composer\Autoload\ClassLoader      $loader
	|	object \Illuminate\Foundation\Application  $app
	|
	| Supported: "composer", "platform", "Closure"
	|
	*/

	'autoload' => 'composer',

	/*
	|--------------------------------------------------------------------------
	| Service Providers
	|--------------------------------------------------------------------------
	|
	| Define your extension service providers here. They will be dynamically
	| registered without having to include them in app/config/app.php.
	|
	*/

	'providers' => [

		'Sanatorium\Rewards\Providers\RewardServiceProvider',
		'Sanatorium\Rewards\Providers\PayoffServiceProvider',

	],

	/*
	|--------------------------------------------------------------------------
	| Routes
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| any custom routing logic here.
	|
	| The closure parameters are:
	|
	|	object \Cartalyst\Extensions\ExtensionInterface  $extension
	|	object \Illuminate\Foundation\Application        $app
	|
	*/

	'routes' => function(ExtensionInterface $extension, Application $app)
	{
		Route::get('invitedby/{by}', ['as' => 'sanatorium.rewards.invite', 'uses' => 'Sanatorium\Rewards\Controllers\Frontend\RewardsController@invite']);

		Route::group([
				'prefix'    => admin_uri().'/rewards/rewards',
				'namespace' => 'Sanatorium\Rewards\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.rewards.rewards.all', 'uses' => 'RewardsController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.rewards.rewards.all', 'uses' => 'RewardsController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.rewards.rewards.grid', 'uses' => 'RewardsController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.rewards.rewards.create', 'uses' => 'RewardsController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.rewards.rewards.create', 'uses' => 'RewardsController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.rewards.rewards.edit'  , 'uses' => 'RewardsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.rewards.rewards.edit'  , 'uses' => 'RewardsController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.rewards.rewards.delete', 'uses' => 'RewardsController@delete']);
			});

		Route::group([
			'prefix'    => 'rewards/rewards',
			'namespace' => 'Sanatorium\Rewards\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.rewards.rewards.index', 'uses' => 'RewardsController@index']);
		});

					Route::group([
				'prefix'    => admin_uri().'/rewards/payoffs',
				'namespace' => 'Sanatorium\Rewards\Controllers\Admin',
			], function()
			{
				Route::get('/' , ['as' => 'admin.sanatorium.rewards.payoffs.all', 'uses' => 'PayoffsController@index']);
				Route::post('/', ['as' => 'admin.sanatorium.rewards.payoffs.all', 'uses' => 'PayoffsController@executeAction']);

				Route::get('grid', ['as' => 'admin.sanatorium.rewards.payoffs.grid', 'uses' => 'PayoffsController@grid']);

				Route::get('create' , ['as' => 'admin.sanatorium.rewards.payoffs.create', 'uses' => 'PayoffsController@create']);
				Route::post('create', ['as' => 'admin.sanatorium.rewards.payoffs.create', 'uses' => 'PayoffsController@store']);

				Route::get('{id}'   , ['as' => 'admin.sanatorium.rewards.payoffs.edit'  , 'uses' => 'PayoffsController@edit']);
				Route::post('{id}'  , ['as' => 'admin.sanatorium.rewards.payoffs.edit'  , 'uses' => 'PayoffsController@update']);

				Route::delete('{id}', ['as' => 'admin.sanatorium.rewards.payoffs.delete', 'uses' => 'PayoffsController@delete']);
			});

		Route::group([
			'prefix'    => 'rewards/payoffs',
			'namespace' => 'Sanatorium\Rewards\Controllers\Frontend',
		], function()
		{
			Route::get('/', ['as' => 'sanatorium.rewards.payoffs.index', 'uses' => 'PayoffsController@index']);

			Route::get('{id}', ['as' => 'sanatorium.rewards.payoffs.redeem', 'uses' => 'PayoffsController@redeem']);
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Database Seeds
	|--------------------------------------------------------------------------
	|
	| Platform provides a very simple way to seed your database with test
	| data using seed classes. All seed classes should be stored on the
	| `database/seeds` directory within your extension folder.
	|
	| The order you register your seed classes on the array below
	| matters, as they will be ran in the exact same order.
	|
	| The seeds array should follow the following structure:
	|
	|	Vendor\Namespace\Database\Seeds\FooSeeder
	|	Vendor\Namespace\Database\Seeds\BarSeeder
	|
	*/

	'seeds' => [

	],

	/*
	|--------------------------------------------------------------------------
	| Permissions
	|--------------------------------------------------------------------------
	|
	| Register here all the permissions that this extension has. These will
	| be shown in the user management area to build a graphical interface
	| where permissions can be selected to allow or deny user access.
	|
	| For detailed instructions on how to register the permissions, please
	| refer to the following url https://cartalyst.com/manual/permissions
	|
	*/

	'permissions' => function(Permissions $permissions)
	{
		$permissions->group('reward', function($g)
		{
			$g->name = 'Rewards';

			$g->permission('reward.index', function($p)
			{
				$p->label = trans('sanatorium/rewards::rewards/permissions.index');

				$p->controller('Sanatorium\Rewards\Controllers\Admin\RewardsController', 'index, grid');
			});

			$g->permission('reward.create', function($p)
			{
				$p->label = trans('sanatorium/rewards::rewards/permissions.create');

				$p->controller('Sanatorium\Rewards\Controllers\Admin\RewardsController', 'create, store');
			});

			$g->permission('reward.edit', function($p)
			{
				$p->label = trans('sanatorium/rewards::rewards/permissions.edit');

				$p->controller('Sanatorium\Rewards\Controllers\Admin\RewardsController', 'edit, update');
			});

			$g->permission('reward.delete', function($p)
			{
				$p->label = trans('sanatorium/rewards::rewards/permissions.delete');

				$p->controller('Sanatorium\Rewards\Controllers\Admin\RewardsController', 'delete');
			});
		});

		$permissions->group('payoff', function($g)
		{
			$g->name = 'Payoffs';

			$g->permission('payoff.index', function($p)
			{
				$p->label = trans('sanatorium/rewards::payoffs/permissions.index');

				$p->controller('Sanatorium\Rewards\Controllers\Admin\PayoffsController', 'index, grid');
			});

			$g->permission('payoff.create', function($p)
			{
				$p->label = trans('sanatorium/rewards::payoffs/permissions.create');

				$p->controller('Sanatorium\Rewards\Controllers\Admin\PayoffsController', 'create, store');
			});

			$g->permission('payoff.edit', function($p)
			{
				$p->label = trans('sanatorium/rewards::payoffs/permissions.edit');

				$p->controller('Sanatorium\Rewards\Controllers\Admin\PayoffsController', 'edit, update');
			});

			$g->permission('payoff.delete', function($p)
			{
				$p->label = trans('sanatorium/rewards::payoffs/permissions.delete');

				$p->controller('Sanatorium\Rewards\Controllers\Admin\PayoffsController', 'delete');
			});
		});
	},

	/*
	|--------------------------------------------------------------------------
	| Widgets
	|--------------------------------------------------------------------------
	|
	| Closure that is called when the extension is started. You can register
	| all your custom widgets here. Of course, Platform will guess the
	| widget class for you, this is just for custom widgets or if you
	| do not wish to make a new class for a very small widget.
	|
	*/

	'widgets' => function()
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Settings
	|--------------------------------------------------------------------------
	|
	| Register any settings for your extension. You can also configure
	| the namespace and group that a setting belongs to.
	|
	*/

	'settings' => function(Settings $settings, Application $app)
	{

	},

	/*
	|--------------------------------------------------------------------------
	| Menus
	|--------------------------------------------------------------------------
	|
	| You may specify the default various menu hierarchy for your extension.
	| You can provide a recursive array of menu children and their children.
	| These will be created upon installation, synchronized upon upgrading
	| and removed upon uninstallation.
	|
	| Menu children are automatically put at the end of the menu for extensions
	| installed through the Operations extension.
	|
	| The default order (for extensions installed initially) can be
	| found by editing app/config/platform.php.
	|
	*/

	'menus' => [

		'admin' => [
			[
				'slug' => 'admin-sanatorium-rewards',
				'name' => 'Rewards',
				'class' => 'fa fa-circle-o',
				'uri' => 'rewards',
				'regex' => '/:admin\/rewards/i',
				'children' => [
					[
						'class' => 'fa fa-circle-o',
						'name' => 'Rewards',
						'uri' => 'rewards/rewards',
						'regex' => '/:admin\/rewards\/reward/i',
						'slug' => 'admin-sanatorium-rewards-reward',
					],
					[
						'class' => 'fa fa-circle-o',
						'name' => 'Payoffs',
						'uri' => 'rewards/payoffs',
						'regex' => '/:admin\/rewards\/payoff/i',
						'slug' => 'admin-sanatorium-rewards-payoff',
					],
				],
			],
		],
		'main' => [
			
		],
	],

];
