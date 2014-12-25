<?php namespace Checkin\Transformers;

class CheckinTransformer extends Transformer {
	
	/**
	 * Transform the admin details array into a response
	 * @param  [type] $feed [description]
	 * @return [type]       [description]
	 */
	public function transform( $details )
	{	
		$details['custom_details'] = $this->userDetail->explodeKeyValueStringToArray($details['custom_details']);

		return [
			'user_details' => $details
		];
	}

}