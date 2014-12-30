<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();
		
		User::create([
			'id' => 1,
		    'email' => "samuel@zaprri.com",
            'password' => Hash::make('123'),
            'group_id' => 2,
            'unique_id' => '7BWgg4AK'	             
		]);

		User::create([
			'id' => 2,
            'email' => "samuel.parry@bigpond.com",
            'password' => Hash::make('123'),
            'group_id' => 1,
            'unique_id' => '7B2gg4AK'             
		]);

		foreach(range(1, 48) as $index)
		{
			User::create([
	             'email' => $faker->email,
	             'password' => Hash::make('123'),
	             'group_id' => $faker->numberBetween($min = 1, $max = 2),
	             'unique_id' => str_random(8)
			]);
		}
	}

}