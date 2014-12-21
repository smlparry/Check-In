<?php

class PagesController extends \BaseController {


	public function landing()
	{
		return View::make('pages.loggedOut');
	}

	public function accessDenied()
	{
		return View::make('pages.accessDenied');
	}
}
