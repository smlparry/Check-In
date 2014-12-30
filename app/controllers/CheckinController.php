<?php

use Checkin\Transformers\FeedTransformer;
use Checkin\Transformers\HistoryTransformer;
use Checkin\Transformers\checkinTransformer;


class CheckinController extends ApiController {

	protected $user;
	protected $checkin;
	protected $userDetail;
	protected $feed;
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
								Feed $feed,
								HistoryTransformer $historyTransformer,
								FeedTransformer $feedTransformer,
								CheckinTransformer $checkinTransformer)
	{
		$this->user = $user;
		$this->checkin = $checkin;
		$this->userDetail = $userDetail;
		$this->feed = $feed;
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
		// Details from form
		$formId	= Input::get('id');
		$adminId = Input::get('parent_id');
		$userId = Auth::id();

		settype($formId, 'integer');

		// Nothing can be null
		if ( ! $formId or ! $adminId or ! $userId )
		{
			return $this->respondInvalidRequest();
		}

		// User that is trying to check in is not what was posted from the form
		if ( $formId !== $userId )
		{
			return $this->respondAccessDenied();
		}

		$admin = $this->user->find( $adminId );

		// Check if requested admin has correct permissions
		if ( $admin->group_id !== 2 )
		{
			return $this->respondInvalidCheckin();
		}

		// Check if the current user has connected to the admin
		if ( ! $this->checkin->hasConnection( $userId, $adminId ) )
		{
			return $this->respondInvalidCheckin( 'You must establish a connection with user before you can check in.');
		}

		$this->checkin->addRecord( $userId, $adminId );

		return $this->respondSuccessfullCheckin( 'Successfully Checked In', $admin );

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

		$feed = $this->checkin->whereParentId( Auth::id() )->get()->toArray();

		if ( ! $feed ) 
		{
			return $this->respondNoResults();
		}

		$connectedUsers = $this->feed->connectedUserIds( $feed );
		$feedUsers = $this->userDetail->whereIn('user_id', $connectedUsers)->get();
		$feedUsers = $this->userDetail->userDetailsToArrayWithUserIdAsKey( $feedUsers );

		$feed = $this->feedTransformer->transformCollection( $feed , $feedUsers );
		return $this->respondWithResults( 'feed_items', $feed );

		// if ( $checkinFeed->verifyGroupId( Auth::id() ) === true ){
		// 	$feed = $checkinFeed->feed( Auth::id() );
		// 	$users = $checkinFeed->feedUsers( $feed ); 
		// 	return View::make( 'checkin.feed', [ 'feed' => $users ] );
		// }

		// return View::make( 'checkin.feed', ['feed' => false] );

	}

	


}
