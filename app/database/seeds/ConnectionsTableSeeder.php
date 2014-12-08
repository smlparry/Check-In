<?php

Class ConnectionsTableSeeder extends Seeder {

	public function run(){

		// to use non Eloquent-functions we need to unguard
        Eloquent::unguard();

        // All existing users are deleted !!!
        DB::table('connections')->delete();

        // Add the seeded records
		DB::table('connections')->insert([
		                          'user_id' => 2,
		                          'connections' => '1'
					        ]); 

	}

}