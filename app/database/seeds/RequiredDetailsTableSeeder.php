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
		                          'required_details' => 'name, address, postcode, phone_number, insurance, book, colour,'
					        ]); 

	}

}