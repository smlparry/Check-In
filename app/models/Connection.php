<?php

class Connection extends Eloquent {

	protected $fillable = array('user_id', 'connections');
	
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
	public function connectionsToArray( $id )
	{	
		
		$connectionsString = $this->connections( $id );
		
		if ( ! empty( $connectionsString ) ){
			return explode( ',' , $connectionsString );
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
		Add a new connection
	*/
	public function addConnection()
	{
		
	}

}