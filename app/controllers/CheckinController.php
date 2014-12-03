<?php

class CheckinController extends \BaseController {

	/*
		Show the check in page
	 */
	public function index()
	{
		return View::make('checkin.before');
	}

	/*
		Series of validation then add the record to teh feed database table
	 */
	public function after() 
	{
		// First check if the user is logged in
		if ( Auth::check() ){

			$checkin = new Feed;
			$formId = Input::get('id');
			$authId = Auth::id();

			// Validation
			if ( $checkin->verifyAuth( $authId, $formId ) === true ){

				// Add the record to the database
				$checkin->user_id = $authId;
				$checkin->save();

				return 'it worked?';

			}

			// The id in the form did not match the one they are logged in with.
			// This could be cause they altered the html in the form and teh hidden feild
			return 'Something went wrong';

		}

		return "YOU MUST BE LOGGED IN FGT";
	}


}
