<?php

class CheckinController extends \BaseController {

	/*
		Show the check in page
	 */
	public function index()
	{
		return View::make('checkin.before');
	}

	public function after() 
	{
		return "this is hwere i do stuff";
	}


}
