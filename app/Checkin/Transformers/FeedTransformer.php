<?php namespace Checkin\Transformers;

class FeedTransformer extends Transformer {
	
	/**
	 * Transform the feed results into a parsable and readable array WITH user details not just id
	 * @param  [type] $feed [description]
	 * @return [type]       [description]
	 */
	public function transform( $feed )
	{
		return [
			'checked_in_user' =>  $this->userDetail->getUserDetailsFeed( $feed['user_id'] ),
			'checked_in_at' => $feed['created_at']
		];
	}

}