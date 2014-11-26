<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email', 64);
			$table->string('password', 64);
			$table->string('remember_token', 100)->nullable();
			$table->integer('group_id');
			$table->integer('parent_id')->nullable();
			$table->string('name')->nullable();
			$table->string('gym')->nullable();
			$table->string('address')->nullable();
			$table->string('DOB')->nullable();
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
		Schema::drop('users');
	}

}
