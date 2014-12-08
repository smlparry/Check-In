<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequiredDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('required_details', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->boolean('name');
			$table->string('name_prompt', 64)->nullable();
			$table->boolean('address');
			$table->string('address_prompt', 64)->nullable();
			$table->boolean('postcode');
			$table->string('postcode_prompt', 64)->nullable();
			$table->boolean('phone_number');
			$table->string('phone_number_prompt', 64)->nullable();
			$table->boolean('custom_details');
			$table->string('custom_details_data')->nullable();
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
		Schema::drop('required_details');
	}

}
