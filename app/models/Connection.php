<?php

class Connection extends Eloquent {


	protected $fillable = array('user_id', 'connections');
	
	public function userDetail()
	{
		return $this->hasOne('UserDetail', 'user_id');
	}	
	/*
		Return the users connections
	 */
	public function connections( $id )
	{
		return $this->whereUserId( Auth::id() )->pluck( 'connections' );
	}

	/*
		Explode the string to an array
	 */
	public function explodeStringToArray( $string )
	{	
		
		if ( ! empty( $string ) ){
			$string = trim( $string , ',' );
			return array_map( 'trim', explode( ',' , $string ) );
		}

		return false;
		
	}

	/*
		Get information for the connected users
	 */
	public function connectionUserDetails( $connections )
	{

		if ( $connections !== false ){

			$user = new User;
				
			foreach ( $connections as $connectedUser ){

				$users = $user->find( $connectedUser );
				$userDetails = $user->userDetails( $connectedUser );

				$connectedUserDetails[] = [
							'user' => $users,
							'user_details' => $userDetails
						];
			}

			return $connectedUserDetails;
		}

		return false;

	}
	/*
		Get the details the admin has specified they require
	 */ 
	public function getRequiredDetails( $id )
	{
		return DB::table('required_details')->where( 'user_id', $id )->pluck( 'required_details' );
	}

	/*
		Turn extra required details into an array
	 */
	public function requiredDetailsToArray( $requiredDetails )
	{	
		if ( $requiredDetails->required_details !== null ){
			$requiredDetailsArray = $this->explodeStringToArray( $requiredDetails->required_details );

			$requiredDetails->required_details = $requiredDetailsArray;

			return $requiredDetails;
		}

		return false;
	}

	/*
		Parse over the input into a string to put into the database
	*/ 
	public function parseRequiredDetails( $input )
	{
		$requiredDetails = '';

		foreach ( $input as $requiredDetail => $value ){
			$requiredDetail = str_replace('require_', '', $requiredDetail);

			if ( $requiredDetail != '_token' ){
				$requiredDetails = $requiredDetails . $requiredDetail. ",";
			}

		}

		return trim( $requiredDetails , ',' );
	}
	/*
		Add the details to the database
	 */
	public function storeRequiredDetails( $input, $id )
	{
		if ( Auth::user( $id )->group_id === 2 ){
			return DB::table('required_details')
					->where( 'user_id' , $id )
					->update( array('required_details' => $input) );
		}
		return false;
	}
	/*
		Display a listing of all the available connections
	 */
	public function availableConnections() 
	{
		return User::with( 'userDetails' )->whereRaw( 'group_id = 2 and id != ' . Auth::id() )->get();
	}
	/*
		Concatinate the connections string with the new conenction, prevent duplicates as well
	 */
	public function concatinateConnections( $existingConnections, $newConnection )
	{

		if ( empty($existingConnections) ){
			$this->makeConnectionRow();
			return $newConnection;
		}

		$connectionsArray = $this->explodeStringToArray( $existingConnections );

		foreach ( $connectionsArray as $connection ){

			settype($connection, 'integer');
			
			if ( $connection === $newConnection){
				return $existingConnections;
			}
		}

		if ( ! empty($newConnection) ){
			$concatinatedString = $existingConnections . ',' . $newConnection;
			return $concatinatedString;
		}

		return $existingConnections;
	}
	/*
		Make new connection row
	 */
	public function makeConnectionRow()
	{
		$this->user_id = Auth::id();
		$this->save();
		return true;
	}
	/* 
		Add a new connection
	*/
	public function addConnection( $user_id, $admin_id )
	{
		settype($admin_id, 'integer');

		// Concatinate the users current connections
		$connections = $this->where( 'user_id', $user_id )->pluck( 'connections' );
		$connections = $this->concatinateConnections( $connections, $admin_id );

		// Concatinate the admins current connected users
		$adminConnections = ConnectedUser::whereUserId( $admin_id )->pluck( 'connected_users' );
		$adminConnections = $this->concatinateConnections( $adminConnections, $user_id );

		// Add both the records to the database
		try {

			$this->whereUserId( $user_id )
				 ->update( ['connections' => $connections] );

			ConnectedUser::whereUserId( $admin_id )
				 ->update( ['connected_users' => $adminConnections ] );

			return true;

		} catch (Exception $e) {

			return false;

		}

	}
	/*
		Determine whether the user has all the details required to connect to the admin
		If the userDetails array contains the required element it will evaluate to false because it is checking if it is null. If it is true it is added to the missingDetails array which is then tested if it is empty and returned if it is not.
		THANKS LARAVEL !!! array_pull is mad
	 */
	public function compareRequiredDetails( $userDetails, $requiredDetails )
	{
		$missingDetails = null;

		if ( ! $requiredDetails )
		{
			return true;
		}

		// Loop over required details
		foreach( $requiredDetails as $requiredDetail ){
			if ( $hasDetails[$requiredDetail] = array_pull( $userDetails, $requiredDetail ) === null ){
				$missingDetails[] = $requiredDetail;
			}
		}

		if ( $missingDetails )
		{
			return $missingDetails;
		}

		return true;

	}
	/*
		Make the rules array for the inputted data
	 */
	public function makeRequiredRules( $details )
	{
		if ( count($details) !== 0 ){
			$rules = [];
			foreach( $details as $key => $value ){
				$rules = array_add($rules, $key, 'required');
			}
			return $rules;
		}
		return false;
	}
	/*
		Validated the required details 
	 */
	public function detailsAreValid( $details, $rules )
	{
		$validation = Validator::make( $details, $rules );

		if ( $validation->passes() ){
			return true;
		}

		return $validation->messages()->toArray();

	}

}