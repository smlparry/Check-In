<?php


class Feed extends Eloquent {

	protected $fillable = array('user_id');
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';


}