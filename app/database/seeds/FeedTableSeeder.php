<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;

class FeedTableSeeder extends Seeder {

	public function run()
	{
		$faker = Faker::create();

		foreach(range(1, 100) as $index)
		{
			Feed::create([
			   	'user_id' => $faker->numberBetween(1,50),
			   	'parent_id' => 1
			]);
		}
	}

}