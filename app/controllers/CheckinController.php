<?php

use Checkin\Transformers\FeedTransformer;
use Checkin\Transformers\HistoryTransformer;
use Checkin\Transformers\checkinTransformer;


class CheckinController extends ApiController {

	protected $user;
	protected $checkin;
	protected $userDetail;
	protected $feedTransformer;
	protected $historyTransformer;
	protected $checkinTransformer;

	/**
	 * [__construct description]
	 * @param FeedTransformer    $feedTransformer    [description]
	 * @param Checkin            $checkin            [description]
	 * @param UserDetail         $userDetail         [description]
	 * @param HistoryTransformer $historyTransformer [description]
	 */
	public function __construct(User $user,
	                            Checkin $checkin,
								UserDetail $userDetail,
								HistoryTransformer $historyTransformer,
								FeedTransformer $feedTransformer,
								CheckinTransformer $checkinTransformer)
	{
		$this->user = $user;
		$this->checkin = $checkin;
		$this->userDetail = $userDetail;
		$this->feedTransformer = $feedTransformer;
		$this->historyTransformer = $historyTransformer;
		$this->checkinTransformer = $checkinTransformer;
	}


	/**
	 * [index description]
	 * @param  [type] $uniqueId [description]
	 * @return [type]           [description]
	 */
	public function index( $uniqueId )
	{
		$parent = $this->user->whereUniqueId( $uniqueId )->pluck( 'id' );

		if ( ! $parent )
		{
			return $this->respondNoResults();
		}

		// Get the details
		$parentDetails = $this->userDetail->with('user')->whereUserId( $parent )->first()->toArray();

		if ( ! $parentDetails )
		{
			return $this->respondNoResults();
		}

		// Check if admin
		if ( $parentDetails['user']['group_id'] !== 2 )
		{
			return $this->respondInvalidCheckin();
		}

		// Transform and respond
		$checkin = $this->checkinTransformer->transform( $parentDetails );
		return $this->respondWithResults( 'details', $checkin );

		//return View::make('checkin.before', ['parentId' => $parentId ]);
	}

	/**
	 * Series of validation then check the user in
	 * @return [type] [description]
	 */
	public function checkUserIn() 
	{
		// First check if the user is logged in
		$formId	= Input::get('id');
		$adminId = Input::get('parent_id');
		$userId = Auth::id();

		// Validation
		if ( $checkin->verifyAuth( $userId, $formId ) === true 
			 && $checkin->verifyGroupId( $adminId ) === true
			 && $checkin->hasConnection( $userId, $adminId ) === true ){

				$checkin->addRecord( $userId, $adminId );
				return Redirect::to('checkin/history')->with( 'success', 'Successfully checked in' );

		}

		// The id in the form did not match the one they are logged in with.
		// this could be cause they altered the html in the form and the hidden feild
		return 'Something went wrong';
	}

	/**
	 * Show the users check in history
	 * @return [type] [description]
	 */
	public function history() 
	{
		
		$history = $this->checkin->with('userDetails')->whereUserId( Auth::id() )->get()->toArray();

		if ( ! $history )
		{
			return $this->respondNoResults();
		}

		$history = $this->historyTransformer->transformCollection( $history );
		return $this->respondWithResults( 'history', $history );

		// $history = $checkin->history( Auth::id() );
		// $parents = $checkin->historyParents( $history ); 
		// return View::make( 'checkin.history', [ 'history' => $parents ] );
	}

	/**
	 * Get the admins feed of checked in users
	 * @return [type]     [description]
	 */
	public function feed()
	{

		$feed = $this->checkin->with('userDetails')->whereParentId( Auth::id() )->get()->toArray();

		if ( ! $feed ) 
		{
			return $this->respondNoResults();
		}

		$feed = $this->feedTransformer->transformCollection( $feed );
		return $this->respondWithResults( 'feed_items', $feed );

		// if ( $checkinFeed->verifyGroupId( Auth::id() ) === true ){
		// 	$feed = $checkinFeed->feed( Auth::id() );
		// 	$users = $checkinFeed->feedUsers( $feed ); 
		// 	return View::make( 'checkin.feed', [ 'feed' => $users ] );
		// }

		// return View::make( 'checkin.feed', ['feed' => false] );

	}

	


}
