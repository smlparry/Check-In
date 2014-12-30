<?php 

class ConnectedUser extends Eloquent {

	protected $fillable = ['user_id', 'connected_users'];
	protected $table = 'connected_users';

	public function connectedUsers( $id )
	{
		return $this->whereUserId( $id )->pluck( 'connected_users' );
	}

}