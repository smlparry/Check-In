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
		if ( Input::has('admin_id') ){
			$adminId = Input::get('admin_id');
		} elseif ( Session::has( 'admin_id' )) {
			$adminId = Session::get( 'admin_id' );
		} else {
			dd('no admin id specified');
		}

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
				return View::make('checkin.connectionResponse')->with( ['response' => $addConnection, 'admin' => $adminId] );
			}

			return View::make('checkin.connectionResponse')->with( ['response' => $comparisonResult, 'admin' => $adminId ]);
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

	/*
		Add required detail(s) when teh user has been asked after attempting to connect
	 */
	public function addRequiredDetails()
	{
		$missing = null;
		$add = null;

		$input = Input::all();
		$adminId = Input::has('admin_id');
		$input = array_except( $input, ['_token', 'admin_id'] );

		if ( count($input) != 0 ){

			$connection = new Connection;
			$rules = $connection->makeRequiredRules( $input );
			$isValid = $connection->detailsAreValid( $input , $rules );

			if ( $isValid === true ){
				return "Success they have entered everything";
			}

		return Redirect::back()->with( ['admin_id' => $adminId, 'errors' => $isValid] )->withInput();

		}
	}

}
