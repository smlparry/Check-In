<?php namespace Checkin\Transformers;

class ConnectedUsersTransformer extends Transformer {

	public function transform( $connectedUsers )
	{
		unset( $connectedUsers['user_id'] );
		return $connectedUsers;
	}

}