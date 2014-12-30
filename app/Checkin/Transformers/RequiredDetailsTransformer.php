<?php namespace Checkin\Transformers;

class RequiredDetailsTransformer extends Transformer {

	public function transform( $requiredDetails )
	{	
		return [
			'required_details' => $requiredDetails['empty'],
			'supplied' => $requiredDetails['supplied']
		];
	}

}