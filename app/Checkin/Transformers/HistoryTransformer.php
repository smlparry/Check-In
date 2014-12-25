<?php namespace Checkin\Transformers;

class HistoryTransformer extends Transformer {

	public function transform( $history )
	{
		$history['user_details']['custom_details'] = $this->userDetail->explodeKeyValueStringToArray($history['user_details']['custom_details']);

		return [
			'checked_in_to' => $history['user_details'],
			'checked_in_time' => $history['created_at']
		];
	}

}