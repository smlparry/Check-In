<?php

use Checkin\Transformers\FeedTransformer;

class CheckinController extends ApiController {

	protected $feedTransformer;
	protected $checkin;

	/**
	 * [__construct description]
	 * @param FeedTransformer $feedTransformer [description]
	 * @param Checkin         $checkin         [description]
	 */
	public function __construct(FeedTransformer $feedTransformer, Checkin $checkin)
	{
		$this->feedTransformer = $feedTransformer;
		$this->checkin = $checkin;
	}


	/**
	 * [index description]
	 * @param  [type] $uniqueId [description]
	 * @return [type]           [description]
	 */
	public function index( $uniqueId )
	{
		$parentId = $checkin->getParentId( $uniqueId );

		return View::make('checkin.before', ['parentId' => $parentId ]);
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
		
		$history = $this->checkin->history( Auth::id() );

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

		$feed = $this->checkin->where( 'parent_id', Auth::id() )->get()->toArray();

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
