<?php

use Checkin\Transformers\ConnectionTransformer;
use Checkin\Transformers\RequiredDetailsTransformer;

class ConnectionController extends ApiController {

	protected $user;
	protected $connection;
	protected $userDetail;
	protected $connectionTransformer;
	protected $requiredDetailsTransformer;

	public function __construct(User $user,
	                            UserDetail $userDetail,
	                            Connection $connection,
	                            ConnectionTransformer $connectionTransformer,
	                            RequiredDetailsTransformer $requiredDetailsTransformer)
	{
		$this->user = $user;
		$this->userDetail = $userDetail;
		$this->connection = $connection;
		$this->connectionTransformer = $connectionTransformer;
		$this->requiredDetailsTransformer = $requiredDetailsTransformer;
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
		###### Figure out a nicer way to do this ########
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

		// User details to array
		$userDetails = $this->userDetail->whereUserId( $userId )->first();
		$userDetails = $this->userDetail->userDetailsFilterForEmpty( $this->userDetail->userDetailsToArray( $userDetails ) );

		if ( ! $userDetails )
		{
			return $this->respondNoUserDetailsFound();
		}

		// Required details to array
		$requiredDetails = $this->connection->getRequiredDetails( $adminId );
		$requiredDetails = $this->connection->explodeStringToArray( $requiredDetails );
			
		// Compare the two arrays to see if all the required details are filled out
		$comparisonResult = $this->connection->compareRequiredDetails( $userDetails, $requiredDetails );

		// This needs to return an error message with the required details that still need to be filled out
		if ( $comparisonResult !== true )
		{
			return $this->respondWithRequiredDetails( $comparisonResult );
		}

		// Add the connection
		$this->connection->addConnection( $userId, $adminId );

		return $this->respondWithSuccess( 'Successfully connected to ' . $this->userDetail->whereUserId( $adminId )->first()->name, $comparisonResult );

	}


	/*
		Add required detail(s) when the user has been asked after attempting to connect
	 */
	public function addDetails()
	{
		$input = Input::all();

		// Admin id for easier redirect if this was called due to missing required details
		$adminId = null;
		if ( Input::has('admin_id') )
		{
			$adminId = Input::get('admin_id');
			// stupid types
			settype( $adminId, 'integer' );
		}

		$input = array_except( $input, ['_token', 'admin_id'] );

		if ( count( $input ) === 0 )
		{
			return $this->respondInvalidRequest();
		}

		// Dynamically create some 'required' rules for the supplied input
		$rules = $this->connection->makeRequiredRules( $input );

		// Validate if the input is correct
		$isValid = $this->connection->detailsAreValid( $input, $rules );

		if ( $isValid !== true )
		{	
			// Have the response contain the supplied details
			$requiredDetails = array_merge( [ 'empty' => $isValid ], [ 'supplied' => $input ] );
			return $this->requiredDetailsTransformer->transform( $requiredDetails );
		}

		// Check if the input contains content for the default columns i.e. name, address, postcode, phone_number
		$defaultValuesToBeUpdated = $this->userDetail->parseForRequiredDefault( $input );

		// Update default values if they are present
		if ( $defaultValuesToBeUpdated )
		{
			$this->userDetail->updateDefaultDetail( $defaultValuesToBeUpdated, $input );
			// Remove these from original input
			$input = $this->userDetail->unsetUpdatedDetails( $defaultValuesToBeUpdated, $input );
		}

		if ( ! $input )
		{
			return $this->respondWithSuccess( 'Successfully updated details.', [ 'admin_id' => $adminId ] );
		}

		// There are custom details to update
		$currentUserDetails = $this->userDetail->whereUserId( Auth::id() )->first();

		if ( ! $currentUserDetails )
		{
			return $this->respondNoUserDetailsFound();
		}

		// Make the new string
		$customDetailsString = $this->userDetail->concatinateCustomDetails( $currentUserDetails->custom_details, $input );
		// Update
		$this->userDetail->addNewCustomDetails( $customDetailsString );

		return $this->respondWithSuccess( 'Successfully updated details', [ 'admin_id' => $adminId ] );

	}
}
