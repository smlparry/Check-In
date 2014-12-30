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
		return [
			'checked_in_user' => $this->feedUsers[$feed['user_id']],
			'checked_in_time' => $feed['created_at']
		];
	}

}