<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		ConnectedUser::truncate();
		Connection::truncate();
		User::truncate();
		Feed::truncate();

		Eloquent::unguard();

		$this->call('UsersTableSeeder');
		$this->call('UserDetailsTableSeeder');
		$this->call('ConnectionsTableSeeder');
		$this->call('RequiredDetailsTableSeeder');
		$this->call('ConnectedUsersTableSeeder');
		$this->call('FeedTableSeeder');
	}

}
