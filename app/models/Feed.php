<?php

class Feed extends \Eloquent {
	protected $fillable = ['user_id', 'parent_id'];
	protected $table = 'feed';
}