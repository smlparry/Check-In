<?php

class TestController extends \BaseController {

	/*
		Random Testing
	 */
	public function index()
	{ 
		$users = User::with('userDetails')->whereId(4)->first();
		var_dump($users->userDetails->name);
	}


}
