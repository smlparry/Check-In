<?php

class AdminController extends ApiController {

	protected $connection;
	
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
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
		$connections = $this->connection->connections( Auth::id() );

		if ( ! $connections )
		{
			return $this->respondNoResults();
		}

		
		dd($connections);
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
