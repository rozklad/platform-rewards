<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayoffsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payoffs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('points');
			$table->integer('payoff_money');
			$table->integer('payoff_id')->nullable();
			$table->string('payoff_type')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payoffs');
	}

}
