<?php

class Connection extends Eloquent {

	protected $fillable = array('user_id', 'connections');
	
	/*
		Simple function to get rid of the array surrounding the returned connections db query object
	 */
	public function unArray( $array )
	{
		foreach ( $array as $unArray ){
			$return = $unArray;
		}

		return $return;
	}
	/*
		Return the users connections
	 */
	public function connections( $id )
	{

		return $this->where( 'user_id', $id )->pluck( 'connections' );

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
		return DB::table('required_details')->where('user_id', $id)->get();
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
		$adminUsers = new User;
		$adminUsers = $adminUsers->where( 'group_id', 2 )->get();
		return $adminUsers;
	}
	/*
		Concatinate the connections string with the new conenction, prevent duplicates as well
	 */
	public function concatinateConnections( $existingConnections, $newConnection )
	{
		if ( empty($existingConnections) ){
			return $newConnection;
		}

		$connectionsArray = $this->explodeStringToArray( $existingConnections );

		foreach ( $connectionsArray as $connection ){
			if ( $connection == $newConnection){
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
		Add a new connection
	*/
	public function addConnection( $user_id, $admin_id )
	{
		if ( $user_id !== $admin_id ){
			$currentConnections = $this->where( 'user_id', $user_id )->pluck('connections');
			$concatinatedConnections = $this->concatinateConnections( $currentConnections, $admin_id );
			return $this->where( 'user_id', $user_id )->update( ['connections' => $concatinatedConnections] );
		}
	}

}