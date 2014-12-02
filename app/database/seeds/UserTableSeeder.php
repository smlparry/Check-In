<?php

Class UserTableSeeder extends Seeder {

	public function run(){

		// to use non Eloquent-functions we need to unguard
        Eloquent::unguard();

        // All existing users are deleted !!!
        DB::table('users')->delete();

        // Add the seeded records
		DB::table('users')->insert([
		                          'email' => "samuel@zaprri.com",
		                          'password' => Hash::make('123'),
		                          'group_id' => 2
					        ]); 

	}

}