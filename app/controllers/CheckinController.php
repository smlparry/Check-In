<?php

class CheckinController extends \BaseController {

	/*
		Show the check in page
	 */
	public function index( $uniqueId )
	{
		$checkInPrep = new Checkin;
		$parentId = $checkInPrep->getParentId( $uniqueId );

		return View::make('checkin.before', ['parentId' => $parentId ]);
	}

	/*
		Series of validation then add the record to teh feed database table
	 */
	public function after() 
	{
		// First check if the user is logged in
		if ( Auth::check() ){

			$checkin = new Checkin;
			$formId	= Input::get('id');
			$parentId = Input::get('parent_id');
			$authId = Auth::id();

			// Validation
			if ( $checkin->verifyAuth( $authId, $formId ) === true 
			  && $checkin->verifyGroupId( $parentId ) === true ){

				$checkin->addRecord( $authId, $parentId );

				return Redirect::to('checkin/history')->with( 'parent_id', $parentId );

			}

			// The id in the form did not match the one they are logged in with.
			// This could be cause they altered the html in the form and the hidden feild
			return 'Something went wrong';

		}

		return "You must be logged in";
	}

	/*
		Show the users check in history
	 */
	public function history() 
	{

		$checkin = new Checkin;
		$history = $checkin->history( Auth::id() );
		$parents = $checkin->historyParents( $history ); 
		return View::make( 'checkin.history', [ 'history' => $parents ] );

	}

}
