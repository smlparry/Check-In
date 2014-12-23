<?php

class AdminController extends \BaseController {

	/*
		Return the dashboard
	 */
	public function dashboard()
	{
		// Return the dashboard with all the variables need
		return View::make("admin.dashboard");
	}
	/*
		Get a list of the connected users 
	 */
	public function connectedUsers()
	{	
		$connections = new Connection;
		$connectedUsers = $connections->connections( Auth::id() );
		$connectedUsers = $connections->explodeStringToArray( $connectedUsers );
		$connectedUserDetails = $connections->connectionUserDetails( $connectedUsers );

		if ( ! empty($connectedUserDetails) ){
			return View::make('checkin.connectedUsers')->with( 'connectedUsers', $connectedUserDetails );
		}

		return View::make('checkin.connectedUsers')->with('connectedUsers', null );
	} 


	/* 
		Allow the admin to specify what information they require
	*/
	public function getRequiredDetails()
	{
		$connection = new Connection;
		$requiredDetails = $connection->getRequiredDetails( Auth::id() );
		$requiredDetails = head( $requiredDetails ); 
		$requiredDetails = $connection->requiredDetailsToArray( $requiredDetails );
		return View::make('admin.requiredDetails')->with('requiredDetails', $requiredDetails);
	}

	/*
		Update the required details table
	*/
	public function storeRequiredDetails()
	{
		$input = Input::all();
		$store = new Connection;
		$parseRequired = $store->parseRequiredDetails( $input );
		$storeRequired = $store->storeRequiredDetails( $parseRequired , Auth::id() );
		if ( $storeRequired !== false ){
			return View::make('admin.requiredDetailsResponse')->with('response', true );
		}

		return View::make('admin.requiredDetailsResponse')->with( 'response', false );
	}
}
