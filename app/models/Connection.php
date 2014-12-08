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
		return explode( ',' , $connectionsString );

	}

	/* 
		Add a new connection
	*/
	public function addConnection()
	{
		
	}

}