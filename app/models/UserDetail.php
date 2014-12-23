<?php


class UserDetail extends Eloquent {

	protected $fillable = array(
	                     	'user_id',
	                     	'name',
	                     	'address',
	                     	'postcode',
	                     	'phone_number',
	                     	'custom_details'
	                     );

	// Table used by the model
	protected $table = 'user_details';

	public function thishappens()
	{
		return $this->all();
	}


	public function getUserDetails( $id )
	{
		return $this->where( 'user_id', $id )->first();
	}
	/*
		Put user details database query into an easy to parse array
		#### Must have user details object passed into function ####
	 */
	public function userDetailsToArray( $userDetailsObject )
	{
		$userDetailsArray = array(
		                        'name' => $userDetailsObject->name,
		                        'address' => $userDetailsObject->address,
		                        'postcode' => $userDetailsObject->postcode,
		                        'phone_number' => $userDetailsObject->phone_number
		                        );
		// add the custom details to the array
		$customUserDetailsArray = $this->explodeKeyValueStringToArray( $userDetailsObject->custom_details );
		$userDetailsArray = array_merge( $userDetailsArray, $customUserDetailsArray );

		return $userDetailsArray;
	}
	/*
		Explode the custom user details strign into an array to be used
		e.g. user,name|this,that -> ['user' => 'name', 'this' => 'that']
	 */
	public function explodeKeyValueStringToArray( $keyValueString )
	{
		$keyValueArray = array();
		if ( ! empty($keyValueString) ){
			$keyValueString = trim( $keyValueString, '|' );
			$keyValues = explode( '|', $keyValueString );
			foreach ( $keyValues as $keyValue ){
				$explodedKeyValue = explode( ',', $keyValue );
				$keyValueArray = array_add( $keyValueArray, $explodedKeyValue['0'], $explodedKeyValue['1'] );
			}
			return $keyValueArray;
		}
		return $keyValueArray;
	}
	

}