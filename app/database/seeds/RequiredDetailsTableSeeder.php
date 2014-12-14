<?php

class RequiredDetailsTableSeeder extends Seeder {

	public function run(){

		// to use non Eloquent-functions we need to unguard
        Eloquent::unguard();

        // All existing users are deleted !!!
        DB::table('required_details')->delete();

        // Add the seeded records
		DB::table('required_details')->insert([
		                          'user_id' => 1,
		                          'name' => true,
		                          'address' => true,
		                          'postcode' => true,
		                          'phone_number' => true,
		                          'custom_details' => false
					        ]); 

	}

}