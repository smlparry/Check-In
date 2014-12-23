<?php

class ConnectionController extends \BaseController {

		/*
		Add a user connection
	 */ 
	public function addConnection()
	{
		$input = Input::all();
		$connections = new Connection;
		$userDetails = new UserDetail;

		$userId = Auth::id();
		$adminId = Input::get('admin_id');
		settype($adminId, 'integer');

		if ( $adminId !== $userId ){

			$usersDetailObject = $userDetails->getUserDetails( $userId );
			$requiredDetails = $connections->getRequiredDetails( $adminId );

			// Parse the objects into arrays
			$userDetailsArray = $userDetails->userDetailsToArray( $usersDetailObject );
			$requiredDetailsArray = $connections->explodeStringToArray( $requiredDetails->required_details );

			$comparisonResult = $connections->compareRequiredDetails( $userDetailsArray, $requiredDetailsArray );

			if ( $comparisonResult === true ){
				$addConnection = $connections->addConnection( Auth::id(), $input['admin_id'] );
				return View::make('checkin.connectionResponse')->with( ['response' => $addConnection, 'admin' => $input['admin_id']] );
			}

			return View::make('checkin.connectionResponse')->with( ['response' => $comparisonResult, 'admin' => $input['admin_id'] ]);
		}

		return "Admin id is the saem as the user id, show a proper error for this. Well dont even let the person get to this stage..";
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

}
