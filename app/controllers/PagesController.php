<?php

class PagesController extends \BaseController {

	public function dashboard()
	{
		// Return the dashboard with all the variables need
		return View::make("pages.dashboard");
	}

}
