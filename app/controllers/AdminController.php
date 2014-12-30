<?php

use Checkin\Transformers\ConnectedUsersTransformer;

class AdminController extends ApiController {

	protected $connection;
	protected $connectedUser;
	protected $userDetail;
	protected $connectedUsersTransformer;
	
	public function __construct(Connection $connection,
	                            ConnectedUser $connectedUser,
	                            UserDetail $userDetail,
	                            ConnectedUsersTransformer $connectedUsersTransformer)
	{
		$this->connection = $connection;
		$this->connectedUser = $connectedUser;
		$this->userDetail = $userDetail;
		$this->connectedUsersTransformer = $connectedUsersTransformer;
	}

	/*
		Return the dashboard
	 */
	public function dashboard()
	{
		// Return the dashboard with all the variables need
		return View::make("admin.dashboard");
	}
	/*
		Get a list of the users who have connected to the admin
	 */
	public function connectedUsers()
	{

		$connectedUsers = $this->connectedUser->whereUserId( Auth::id() )->pluck('connected_users');

		if ( ! $connectedUsers )
		{
			return $this->respondNoResults();
		}

		// Parse the connectedUsers string
		$connectedUsers = $this->connection->explodeStringToArray( $connectedUsers );
		$connectedUsers = $this->userDetail->whereIn( 'user_id', $connectedUsers )->get()->toArray();
		
		// Transform and respond
		$connectedUsers = $this->connectedUsersTransformer->transformCollection( $connectedUsers );
		return $this->respondWithResults('connected_users', $connectedUsers);

	} 


	/* 
		Allow the admin to specify what information they require
	*/
	public function getRequiredDetails()
	{
		$connection = new Connection;
		$requiredDetails = $connection->getRequiredDetails( Auth::id() );
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
