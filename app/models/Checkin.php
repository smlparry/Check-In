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
		Establish user relationship
	 */
	public function hasParent()
	{
		return $this->hasOne( 'User', 'id' );
	}

	/*
		Establish parent details relationship
	 */
	public function hasParentDetails()
	{
		return $this->hasOne( 'UserDetail', 'user_id' );
	}

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

	/*
		Add the record to the database DO VALIDATION BEFORE!
	 */
	public function addRecord( $authId, $parentId )
	{
		$this->user_id = $authId;
		$this->parent_id = $parentId;
		$this->save();

		return true;
	}

	/*
		Get the users check in history
	 */
	public function history( $id )
	{
		return $this->where( 'user_id', $id )->get();
	}

	/*
		Get the users history parents data. I.E the details of the places they have checked in at
	 */
	public function historyParents( $history )
	{	

		foreach ( $history as $historyItem ){

			$historyParents = $this->find( $historyItem['parent_id'] )
								   ->hasParent;

			$historyParentDetails = $this->find( $historyItem['parent_id'] )
			                             ->hasParentDetails;

			$historyData[] = [
					'parent_data' => $historyParents, 
					'parent_details_data' => $historyParentDetails,
					'user_checked_in_data' => $historyItem
			];

		}

		return $historyData;

	}
}