<?php

class Feed extends \Eloquent {

	protected $fillable = ['user_id', 'parent_id'];
	protected $table = 'feed';
	protected $userDetail;

	public function __construct(UserDetail $userDetail)
	{
		$this->userDetail = $userDetail;
	}

	// Takes an array of user details for all the users in the feed
	public function feedUserDetails( $feedUsers )
	{
		$feedUserDetails = array();
		foreach( $feedUsers as $userDetails )
		{
			$userDetails->custom_details = $this->userDetail->explodeKeyValueStringToArray( $userDetails->custom_details ); 
			
			$feedUserDetails = array_add( $feedUserDetails, $userDetails->user_id, $userDetails->toArray() ); 
		}

		return $feedUserDetails;
	}

	// Takes an array of the admins feed and returns an array of user_ids of who has checked in
	public function connectedUserIds( $feed )
	{
		foreach ( $feed as $feedItem )
		{
			$connectedUsers[] = $feedItem['user_id'];
		}
		return $connectedUsers;
	}
}