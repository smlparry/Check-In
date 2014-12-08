<?php

Class UserDetailsTableSeeder extends Seeder {

	public function run(){

		// to use non Eloquent-functions we need to unguard
        Eloquent::unguard();

        // All existing users are deleted !!!
        DB::table('user_details')->delete();

        // Add the seeded records
		DB::table('user_details')->insert([
		                          'user_id' => 1,
		                          'name' => 'Samuel Parry',
		                          'address' => 'This address',
		                          'postcode' => '3216',
		                          'phone_number' => '0435061054',
		                          'custom_details' => 'Here go the details'
					        ]); 

	}

}