<?php

use Faker\Factory as Faker;

class ConnectionsTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 50) as $index)
		{
			$connections = $faker->numberBetween($min = 1, $max = 50) . ',' . 
							$faker->numberBetween($min = 1, $max = 50) . ',' .
							$faker->numberBetween($min = 1, $max = 50) . ',' .
							$faker->numberBetween($min = 1, $max = 50);

			Connection::create([
                  'user_id' => $index,
                  'connections' => $connections
			]);
		}
	}

}