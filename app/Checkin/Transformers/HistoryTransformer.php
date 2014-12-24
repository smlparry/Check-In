<?php namespace Checkin\Transformers;

class HistoryTransformer extends Transformer {

	public function transform( $history )
	{
		return [
			'checked_in_to' => $this->userDetail->getUserDetails()
		];
	}

}