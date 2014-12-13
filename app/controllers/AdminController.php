<?php

class AdminController extends \BaseController {

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

			return View::make('checkin.connectedUsers');
		} 

		return View::make('checkin.connectedUsers')->with( 'connectedUsers', false );

	}

}
