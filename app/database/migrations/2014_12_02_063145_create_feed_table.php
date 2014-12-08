<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('feed', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('parent_id');
			$table->date('created_at');
			$table->date('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('feed', function(Blueprint $table)
		{
			Schema::drop('feed');
		});
	}

}
