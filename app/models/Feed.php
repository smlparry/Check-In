<?php


class Feed extends Eloquent {

	protected $fillable = array('user_id');
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'feed';

	/*
		Simple function to verify the html in the form has not changed
	 */
	public function verifyAuth( $authId, $formId ){

		if ( $authId == $formId ){
			return true;
		}

		return false;

	}


}