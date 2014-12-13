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

	public function addUser( $id )
	{
		$this->user_id = $id;
		$this->name = 'Not specified';
		$this->save();
	}
	

}