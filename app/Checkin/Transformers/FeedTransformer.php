<?php namespace Checkin\Transformers;

class FeedTransformer {
		
	protected $feedUsers;

	public function transformCollection( array $feed, array $feedUsers )
	{
		$this->feedUsers = $feedUsers;
		return array_map( [$this, 'transform'], $feed );
	}
	/**
	 * Transform the feed results into a parsable and readable array WITH user details not just id
	 * @param  [type] $feed [description]
	 * @return [type]       [description]
	 */
	public function transform( $feed )
	{	
		$userKey = $feed['user_id'];
		unset( $this->feedUsers[$userKey]['user_id'] );

		return [
			'checked_in_user' => $this->feedUsers[$userKey],
			'checked_in_time' => $feed['created_at']
		];
	}

}