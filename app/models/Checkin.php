<?php


class Checkin extends Eloquent {

	protected $fillable = array('user_id');
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'feed';

	/* 
		Get the parent id from the unique id
	*/
	public function getParentId( $uniqueId )
	{

		if ( Auth::check() ){
			return DB::table('users')->where( 'unique_id', $uniqueId )->pluck('id');
		}

		return false;

	}
	/*
		Simple function to verify the html in the form has not changed
	 */
	public function verifyAuth( $authId, $formId )
	{

		if ( $authId == $formId ){
			return true;
		}

		return false;

	}

	/*
		To verify they are indeed an admin
	 */
	public function verifyGroupId( $id )
	{

		if ( User::find( $id )->group_id === 2 ){
			return true;
		}

		return false;

	}


}