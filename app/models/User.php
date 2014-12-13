<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = array('email', 'password', 'group_id');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	/* 
		Simple function to return an array of the input
	*/

	public function userDataToArray( $input ){

		return array(
		 	'email' => $input['email'],
		 	'password' => $input['password']
		);

	}

	/*
		Rules for registration
	 */
	
	public function rulesRegister(){
		return array(
		        'email' => 'Required|Email|Unique:users,email',
				'password' => 'Required'
			);
	}

	public function rulesLogin(){
		return array(
		        'email' => 'Required|Email',
				'password' => 'Required'
			);
	}
	/*
		Check if the user data supplied is valid and ready to be registered
	 */
	
	public function isValid( $userData, $rules )
	{

		$validation = Validator::make( $userData, $rules );

		if ( $validation->passes() ){
			return true;
		}

		// Validation did not pass
		return $validation->messages();

	}

	/*
		Attempt to register the user MAKE SURE YOU HAVE VALIDATED BEFORE!
	 */

	public function registerUser( $userData )
	{

		$this->email = $userData['email'];
		$this->password = Hash::make( $userData['password'] );
		$this->group_id = 1;
		$this->unique_id = str_random(8);
		$this->save();
		
	}

	/*
		Fire off event for the confirmation email
	 */
	
	public function confirmationEmail( $userData ) {

		// Do something here
		return true;
	}


	/*
	 	Attempt to log the user in and just return true or false depending
	 */

	public function logUserIn( $userData )
	{

		if ( Auth::attempt( $userData , true ) ){
			return true;
		}

		return $this->errorMessages;
	}

}
