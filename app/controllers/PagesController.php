<?php

class PagesController extends \BaseController {


	public function landing()
	{
		return View::make('pages.logged_out');
	}

}
