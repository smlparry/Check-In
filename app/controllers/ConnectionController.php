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

		// Done because of redirecting needs the admin id
		if ( Input::has('admin_id') ){
			$adminId = Input::get('admin_id');
		} elseif ( Session::has( 'admin_id' )) {
			$adminId = Session::get( 'admin_id' );
		} else {
			$adminId = null;
		}

		settype($adminId, 'integer');

		if ( $adminId !== $userId ){

			$usersDetailObject = $userDetails->getUserDetails( $userId );
			$requiredDetails = $connections->getRequiredDetails( $adminId );

			// Parse the objects into arrays
			$userDetailsArray = $userDetails->userDetailsToArray( $usersDetailObject );
			$requiredDetailsArray = $connections->explodeStringToArray( $requiredDetails->required_details );

			// Compare the two arrays to see if all the required details are filled out
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
		$input = Input::all();
		$adminId = Input::has('admin_id');
		$input = array_except( $input, ['_token', 'admin_id'] );

		if ( count($input) != 0 ){

			$connection = new Connection;
			$rules = $connection->makeRequiredRules( $input );
			$isValid = $connection->detailsAreValid( $input , $rules );

			if ( $isValid === true ){
				// Update the default values if it is required
				$userDetail = new UserDetail;
				$defaultValuesToBeUpdated = $userDetail->parseForEmptyDefault( $input );

				if ( $defaultValuesToBeUpdated !== false ){
					$userDetail->updateDefaultDetail( $defaultValuesToBeUpdated, $input );
					$input = $userDetail->unsetUpdatedDetails( $defaultValuesToBeUpdated, $input);
				}

				// Update the custom details
				if ( ! empty($input) ){
					$userDetails = $userDetail->getUserDetails( Auth::id() );
					$currentCustomDetails = $userDetails->custom_details;

					$customDetailsToUpdateString = $userDetail->concatinateCustomDetails( $currentCustomDetails, $input );
					$userDetail->addNewCustomDetails( $customDetailsToUpdateString );
				}

				$this->addConnection()->with('adminId', $adminId);
				return View::make('checkin.connectionResponse')->with(['response' => true, 'admin' => $adminId ]);


			}

			return Redirect::back()->with( ['admin_id' => $adminId, 'errors' => $isValid] )->withInput();

		}
	}
}
