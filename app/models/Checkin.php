<?php


class Checkin extends Eloquent {

	protected $fillable = array('user_id');
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'feed';
	protected $hidden = ['id', 'updated_at'];

	public function userDetails()
	{
		return $this->hasOne('UserDetail', 'user_id');
	}
	/*
		Establish user relationship
	 */
	public function getParent( $adminId )
	{
		return DB::table('users')->where( 'id', $adminId )->get();
	}

	/* 
		Get the parent id from the unique id
	*/
	public function getParentId( $uniqueId )
	{

		return DB::table('users')->where( 'unique_id', $uniqueId )->pluck('id');
		
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
		Check if the user is already connected to the place they are trying to connect to
	 */
	public function hasConnection( $userId, $adminId )
	{
		$connections = new Connection;
		$availableConnections = $connections->connections( $userId );
		$availableConnections = $connections->explodeStringToArray( $availableConnections );

		if ( ! empty($availableConnections) ){
			foreach ( $availableConnections as $availableConnection ){
				if ( $availableConnection === $adminId ){
					return true;
				}
			}
		}

		return false;
	}
	/*
		Get the admins required details.
	 */
	public function getRequiredDetails( $adminId )
	{
		return DB::table('required_details')->where( 'user_id' , $adminId )->pluck('required_details');
	}
	/*
		Add the record to the database DO VALIDATION BEFORE!
	 */
	public function addRecord( $authId, $adminId )
	{
		$this->user_id = $authId;
		$this->parent_id = $adminId;
		$this->save();

		return true;
	}

	/*
		Get the users check in history
	 */
	public function history( $id )
	{
		return $this->where( 'user_id', $id )->get()->toArray();
	}

	/*
		Get the users history parents data. I.E the details of the places they have checked in at
	 */
	public function historyParents( $history )
	{	

		if ( count( $history ) != 0 ){

			$parentDetail = new UserDetail;

			foreach ( $history as $historyItem ){

				$historyParentDetails = $parentDetail->getUserDetails( $historyItem['parent_id'] );

				$historyData[] = [
						'parent_details_data' => $historyParentDetails,
						'user_checked_in_data' => $historyItem
					];

			}

			return $historyData;

		}

		return false;

	}

	/*
		Get the admins check in history of users
	 */
	public function feed( $id )
	{
		return $this->where( 'parent_id', $id )->get();
	}

	/*
		Get the details for the users who have connected
	 */
	public function feedUsers( $feed )
	{	

		if ( count($feed) !== 0 ){

			$userDetail = new UserDetail;

			foreach( $feed as $feedItem ){

				$feedDetails[] = [
						'user' => User::find( $feedItem->user_id ),
						'user_details' => $userDetail->getUserDetails( $feedItem->user_id )
					];

			}	

			return $feedDetails;

		}

		return [];
	}
}