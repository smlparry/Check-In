<?php

use Checkin\Transformers\ConnectionTransformer;

class ConnectionController extends ApiController {

	protected $user;
	protected $connection;
	protected $userDetail;
	protected $connectionTransformer;

	public function __construct(User $user,
	                            UserDetail $userDetail,
	                            Connection $connection,
	                            ConnectionTransformer $connectionTransformer)
	{
		$this->user = $user;
		$this->userDetail = $userDetail;
		$this->connection = $connection;
		$this->connectionTransformer = $connectionTransformer;
	}

	/*
		Display a listing of all the places the user can connect to
	 */
	public function connect()
	{
		$connections = $this->connection->availableConnections()->toArray();

		if ( ! $connections )
		{
			return $this->respondNoResults();
		}

		$connections = $this->connectionTransformer->transformCollection( $connections );
		return $this->respondWithResults( 'data' , $connections );

	}

	/*
		Add a user connection
	 */ 
	public function addConnection()
	{
		$userId = Auth::id();

		// Admin id needs to be retained when adding extra details
		if ( Input::has('admin_id') ){
			$adminId = Input::get('admin_id');
		} elseif ( Session::has( 'admin_id' )) {
			$adminId = Session::get( 'admin_id' );
		} 

		// stupid types
		settype($adminId, 'integer');

		if ( ! $userId or ! $adminId )
		{
			return $this->respondInvalidRequest();
		}

		if ( $adminId === $userId )
		{
			return $this->respondAccessDenied( 'Cannot connect to yourself.' );
		}

		$userDetails = $this->userDetail->find( $userId )->first()->toArray();
		$userDetails['custom_details'] = $this->userDetail->explodeKeyValueStringToArray( $userDetails['custom_details'] );

		$requiredDetails = $this->connection->getRequiredDetails( $adminId );
		$requiredDetails = $this->connection->explodeStringToArray( $requiredDetails );
			
		// Compare the two arrays to see if all the required details are filled out
		$comparisonResult = $this->connection->compareRequiredDetails( $userDetails, $requiredDetails );

		##############
		// This needs to return an error message with the required details that still need to be filled out
		#############
		if ( $comparisonResult !== true )
		{
			return $this->returnWithRequiredDetails( $comparisonResult );
		}

		dd( $comparisonResult );

			if ( $comparisonResult === true ){
				$addConnection = $connections->addConnection( Auth::id(), $input['admin_id'] );
				return View::make('checkin.connectionResponse')->with( ['response' => $addConnection, 'admin' => $adminId] );
			}

			return View::make('checkin.connectionResponse')->with( ['response' => $comparisonResult, 'admin' => $adminId ]);

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
