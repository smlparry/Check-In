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

		// Check if any of the details are empty
		foreach ( $userDetailsArray as $key => $emptyCheck ){
			if ( strlen($emptyCheck) === 0 ){
				unset( $userDetailsArray[ $key ] );
			}
		}

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
	/*
		Parses the required details array for the default values that may need to be added
		This function takes an array of the details to be updated, sees if any of them are the default values and therefore are not added to the custom_details string. 
		This does not check if they are 'not specified' or not
		Check if the user has not filled out the default user details (name, address, postcode, phone_number)
	 */
	public function parseForEmptyDefault( $array )
	{
		$defaultDetails = array('name', 'address', 'postcode', 'phone_number');
		foreach( $defaultDetails as $detail ){
			if ( $updateDefault[$detail] = array_pull( $array, $detail ) !== null ){
				$toUpdate[] = $detail;
			}
		}

		if ( ! empty($toUpdate) ){
			return $toUpdate;
		}

		return false;
	}
	/*
		Update the users default values
	 */
	public function updateDefaultDetail( $defaultValues, $userUpdate )
	{
		if ( $defaultValues !== false ){			
			foreach ( $defaultValues as $defaultValue ){
				$this->where( 'user_id', Auth::id() )->update( [$defaultValue => $userUpdate[$defaultValue] ]);
			}
			return true;
		}

		return true;
	}
	/*
		Remove the details from the input array that have already been updated
	 */
	public function unsetUpdatedDetails( $updated, $input )
	{
		foreach( $updated as $unsetKey ){
			unset( $input[$unsetKey]);
		}

		return $input;
	}
	/*
		Concatinate custom user details
	 */
	public function concatinateCustomDetails( $currentCustomDetails, $array )
	{
		foreach ( $array as $key => $value ){
			$currentCustomDetails = $currentCustomDetails. '|' . $key . ',' . $value;
	 	}
	 	$customDetailsString = trim($currentCustomDetails, '|');

	 	return $customDetailsString;
	}
	/*
		Add the details that the user has entered when trying to connect (custom details)
 		A string of pre formatted custom details needs to be passed into this function
	 */
	public function addNewCustomDetails( $customDetailsString )
	{
		return $this->where( 'user_id' , Auth::id() )->update( ['custom_details' => $customDetailsString] );

	}

}