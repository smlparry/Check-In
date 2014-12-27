<?php namespace Checkin\Transformers;

class ConnectionTransformer extends Transformer
{
	public function transform( $connections )
	{
			$connections['user_details']['custom_details'] = $this->userDetail->explodeKeyValueStringToArray($connections['user_details']['custom_details']);

			return $connections['user_details'];
	}
}