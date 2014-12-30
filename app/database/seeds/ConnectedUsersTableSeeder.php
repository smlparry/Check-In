<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class ConnectedUsersTableSeeder extends Seeder {

	public function run()
	{

		$faker = Faker::create();

		foreach(range(1, 50) as $index){
		
			$connectedUsers = $faker->numberBetween($min = 1, $max = 50) . ',' . 
								$faker->numberBetween($min = 1, $max = 50) . ',' .
								$faker->numberBetween($min = 1, $max = 50) . ',' .
								$faker->numberBetween($min = 1, $max = 50);
								
			ConnectedUser::create([
	            'user_id' => $index,
	            'connected_users' => $connectedUsers
			]);

		}
	}

}