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
		if ( Auth::user()->group_id === 2){
			$connections = new Connection;
			$connectedUsers = $connections->connectionsToArray( Auth::id() );
			$connectedUserDetails = $connections->connectionUserDetails( $connectedUsers );

			if ( ! empty($connectedUserDetails) ){
				return View::make('checkin.connectedUsers')->with( 'connectedUsers', $connectedUserDetails );
			}

			return View::make('checkin.connectedUsers')->with('connectedUsers', null );
		} 

		return View::make('checkin.connectedUsers')->with( 'connectedUsers', false );

	}

	/* 
		Allow the admin to specify what information they require
	*/
	public function requiredDetails()
	{
		$connection = new Connection;
		$requiredDetails = $connection->getRequiredDetails();
		return View::make('admin.requiredDetails');
	}
}
