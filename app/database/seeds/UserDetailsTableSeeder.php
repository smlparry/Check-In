<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UserDetailsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 50) as $index)
		{
			UserDetail::create([
				'user_id' => $index,
                'name' => $faker->name,
                'address' => $faker->streetAddress,
                'postcode' => $faker->postcode,
                'phone_number' => $faker->phoneNumber,
                'custom_details' => 'custom,details'
			]);
		}
	}

}