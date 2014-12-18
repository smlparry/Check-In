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
			$connectedUsers = $connections->connections( Auth::id() );
			$connectedUsers = $connections->explodeStringToArray( $connectedUsers );
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
	public function getRequiredDetails()
	{
		if ( Auth::user()->group_id === 2 ){
			$connection = new Connection;
			$requiredDetails = $connection->getRequiredDetails( Auth::id() );
			$requiredDetails = $connection->unArray( $requiredDetails ); 
			$requiredDetails = $connection->customDetailsToArray( $requiredDetails );
			return View::make('admin.requiredDetails')->with('requiredDetails', $requiredDetails);
		}

		return View::make('admin.requiredDetails')->with( 'requiredDetails', false );
	}

	/*
		Update the required details table
	
	*/
	public function storeRequiredDetails()
	{
		
	}
}
