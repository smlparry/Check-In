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
		Check the user in
	 */
	public function checkUserIn() 
	{
		// First check if the user is logged in
		$checkin = new Checkin;
		$formId	= Input::get('id');
		$adminId = Input::get('parent_id');
		$userId = Auth::id();

		// Validation
		if ( $checkin->verifyAuth( $userId, $formId ) === true 
			 && $checkin->verifyGroupId( $adminId ) === true
			 && $checkin->hasConnection( $userId, $adminId ) === true ){


			if ( $checkin->compareRequiredDetails( $usersDetails, $requiredDetails ) === true ){

				$checkin->addRecord( $userId, $adminId );
				return Redirect::to('checkin.history')->with( 'success', 'Successfully checked in' );

			}


		}

		// The id in the form did not match the one they are logged in with.
		// This could be cause they altered the html in the form and the hidden feild
		return 'Something went wrong';
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

	public function feed()
	{
		$checkinFeed = new Checkin;

		if ( $checkinFeed->verifyGroupId( Auth::id() ) === true ){
			$feed = $checkinFeed->feed( Auth::id() );
			$users = $checkinFeed->feedUsers( $feed ); 
			return View::make( 'checkin.feed', [ 'feed' => $users ] );
		}

		return View::make( 'checkin.feed', ['feed' => false] );

	}

	/*
		display a listing of all the places the user can connect to
	 */
	public function connect()
	{
		$connections = new Connection;
		$connections = $connections->availableConnections();
		return View::make('checkin.connect')->with('availableConnections', $connections);
	}

	/*
		Add a user connection
	 */ 
	public function addConnection()
	{
		$input = Input::all();
		$connections = new Connection;

		// Check if the user meets all the required details, if not return with the details they need to add
		$usersDetails = $connections->getUserDetails( $userId );
		$requiredDetails = $connections->getRequiredDetails( $adminId );
		

		if ($connections->compareRequiredDetails( $usersDetails, $requiredDetails ) === true){
			$addConnection = $connections->addConnection( Auth::id(), $input['admin_id'] );
			return View::make('/checkin/connectionResponse')->with( ['response' => $addConnection, 'admin' => $input['admin_id']] );
		}

		return Redirect::to('/checkin/connect');
	}

}
