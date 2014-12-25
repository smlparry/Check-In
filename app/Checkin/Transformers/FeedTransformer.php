<?php namespace Checkin\Transformers;

class FeedTransformer extends Transformer {
	
	/**
	 * Transform the feed results into a parsable and readable array WITH user details not just id
	 * @param  [type] $feed [description]
	 * @return [type]       [description]
	 */
	public function transform( $feed )
	{	
		$feed['user_details']['custom_details'] = $this->userDetail->explodeKeyValueStringToArray($feed['user_details']['custom_details']);

		return [
			'checked_in_user' =>  $feed['user_details'],
			'checked_in_time' => $feed['created_at']
		];
	}

}